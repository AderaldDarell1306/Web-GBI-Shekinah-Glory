<?php

namespace App\Support;

use ZipArchive;
use Exception;

class SimpleXlsxReader
{
    /**
     * Baca seluruh lembar kerja (sheets) dalam file Excel (.xlsx) dan satukan baris-baris datanya
     *
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    public static function read(string $filePath): array
    {
        $allSheets = self::readSheets($filePath);
        if (empty($allSheets)) {
            return [];
        }

        $combined = [];
        $header = null;

        foreach ($allSheets as $sheetName => $rows) {
            if (empty($rows)) continue;

            $sheetHeader = array_shift($rows);

            if ($header === null) {
                $header = $sheetHeader;
                $combined[] = $header;
            }

            foreach ($rows as $row) {
                $combined[] = $row;
            }
        }

        return $combined;
    }

    /**
     * Baca file Excel (.xlsx) dan kembalikan array asosiatif per nama sheet: ['Sheet1' => [rows], ...]
     *
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    public static function readSheets(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("File Excel tidak ditemukan: {$filePath}");
        }

        $zip = new ZipArchive();
        $status = $zip->open($filePath);
        if ($status !== true) {
            throw new Exception("Gagal membuka file Excel (.xlsx). Pastikan format file adalah .xlsx yang valid.");
        }

        // 1. Ekstrak Shared Strings (xl/sharedStrings.xml)
        libxml_use_internal_errors(true);
        $sharedStrings = [];
        $sharedStringsIndex = $zip->locateName('xl/sharedStrings.xml', ZipArchive::FL_NODIR);
        if ($sharedStringsIndex === false) {
            $sharedStringsIndex = $zip->locateName('xl/sharedStrings.xml');
        }

        if ($sharedStringsIndex !== false) {
            $xmlContent = $zip->getFromIndex($sharedStringsIndex);
            if ($xmlContent) {
                $xmlContent = preg_replace('/\s+xmlns(:[a-zA-Z0-9_]+)?="[^"]*"/i', '', $xmlContent);
                $xmlContent = preg_replace('/<(\/)?([a-zA-Z0-9_]+):/', '<$1', $xmlContent);
                $xmlContent = preg_replace('/\s+([a-zA-Z0-9_]+):([a-zA-Z0-9_]+)="[^"]*"/', '', $xmlContent);
                $xml = simplexml_load_string($xmlContent);
                if ($xml && isset($xml->si)) {
                    foreach ($xml->si as $si) {
                        if (isset($si->t)) {
                            $sharedStrings[] = (string) $si->t;
                        } elseif (isset($si->r)) {
                            $str = '';
                            foreach ($si->r as $r) {
                                if (isset($r->t)) {
                                    $str .= (string) $r->t;
                                }
                            }
                            $sharedStrings[] = $str;
                        } else {
                            $sharedStrings[] = '';
                        }
                    }
                }
            }
        }

        // 2. Baca Relasi Workbook (xl/_rels/workbook.xml.rels)
        $rels = [];
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if ($relsXml) {
            preg_match_all('/<Relationship\s+([^>]+)>/i', $relsXml, $relMatches);
            foreach ($relMatches[1] as $rAttr) {
                preg_match('/Id="([^"]+)"/i', $rAttr, $mId);
                preg_match('/Target="([^"]+)"/i', $rAttr, $mTarget);
                if (!empty($mId[1]) && !empty($mTarget[1])) {
                    $target = $mTarget[1];
                    if (!str_starts_with($target, 'xl/')) {
                        $target = 'xl/' . ltrim($target, '/');
                    }
                    $rels[$mId[1]] = $target;
                }
            }
        }

        // 3. Baca Daftar Sheet Terurut dari xl/workbook.xml
        $sheets = [];
        $wbXml = $zip->getFromName('xl/workbook.xml');
        if ($wbXml) {
            preg_match_all('/<sheet\s+([^>]+)>/i', $wbXml, $sheetMatches);
            foreach ($sheetMatches[1] as $sAttr) {
                preg_match('/name="([^"]+)"/i', $sAttr, $mName);
                preg_match('/r:id="([^"]+)"/i', $sAttr, $mRId);
                $sName = $mName[1] ?? 'Sheet';
                $rId = $mRId[1] ?? '';
                $targetFile = $rels[$rId] ?? null;
                if ($targetFile) {
                    $sheets[$sName] = $targetFile;
                }
            }
        }

        // Fallback jika workbook.xml tidak terbaca
        if (empty($sheets)) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('#xl/worksheets/sheet\d+\.xml#i', $name)) {
                    $sheets['Sheet' . ($i + 1)] = $name;
                }
            }
        }

        // 4. Parse Tiap Sheet
        $allSheetData = [];
        foreach ($sheets as $sheetName => $fileInZip) {
            $content = $zip->getFromName($fileInZip);
            if ($content) {
                $rows = self::parseSheetXml($content, $sharedStrings);
                if (!empty($rows)) {
                    $allSheetData[$sheetName] = $rows;
                }
            }
        }

        $zip->close();
        libxml_clear_errors();

        return $allSheetData;
    }

    /**
     * Parse XML satu worksheet
     */
    protected static function parseSheetXml(string $sheetXmlContent, array $sharedStrings): array
    {
        $sheetXmlContent = preg_replace('/\s+xmlns(:[a-zA-Z0-9_]+)?="[^"]*"/i', '', $sheetXmlContent);
        $sheetXmlContent = preg_replace('/<(\/)?([a-zA-Z0-9_]+):/', '<$1', $sheetXmlContent);
        $sheetXmlContent = preg_replace('/\s+([a-zA-Z0-9_]+):([a-zA-Z0-9_]+)="[^"]*"/', '', $sheetXmlContent);
        $xml = simplexml_load_string($sheetXmlContent);

        if (!$xml || !isset($xml->sheetData) || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        $globalMaxCol = 0;

        foreach ($xml->sheetData->row as $row) {
            $rowCells = [];
            foreach ($row->c as $cell) {
                $ref = (string) $cell['r'];
                $colLetters = preg_replace('/[0-9]/', '', $ref);
                $colIdx = self::colLetterToIndex($colLetters);

                if ($colIdx > $globalMaxCol) {
                    $globalMaxCol = $colIdx;
                }

                $type = (string) $cell['t'];
                $val = '';

                if ($type === 's') {
                    $idx = (int) $cell->v;
                    $val = $sharedStrings[$idx] ?? '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $val = (string) $cell->is->t;
                } elseif ($type === 'b') {
                    $val = ((string) $cell->v === '1') ? '1' : '0';
                } elseif ($type === 'e') {
                    $val = '';
                } elseif (isset($cell->v)) {
                    $val = (string) $cell->v;
                }

                $rowCells[$colIdx] = $val;
            }
            $rows[] = $rowCells;
        }

        $normalizedRows = [];
        foreach ($rows as $cells) {
            $rowArray = [];
            for ($c = 0; $c <= $globalMaxCol; $c++) {
                $rowArray[$c] = isset($cells[$c]) ? trim($cells[$c]) : '';
            }

            $hasContent = false;
            foreach ($rowArray as $v) {
                if ($v !== '') {
                    $hasContent = true;
                    break;
                }
            }

            if ($hasContent) {
                $normalizedRows[] = $rowArray;
            }
        }

        return $normalizedRows;
    }

    /**
     * Konversi kode huruf kolom Excel ke 0-based index
     */
    public static function colLetterToIndex(string $col): int
    {
        $col = strtoupper(trim($col));
        if ($col === '') {
            return 0;
        }

        $len = strlen($col);
        $index = 0;
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($col[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }
}
