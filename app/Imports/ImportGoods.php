<?php

namespace App\Imports;

use App\Models\Good;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\DB;

class ImportGoods implements OnEachRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $additionalAttributeHeaders = [];

    public function onRow(Row $row)
    {
        DB::transaction(function () use ($row) {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            if ($rowIndex != 1) {
                $good = Good::updateOrCreate(
                    ['external_code' => $row[5]],
                    [
                        'name' => $row[4],
                        'description' => $row[17],
                        'price' => floatval(str_replace(",", ".", $row[8])),
                        'discount' => floatval(str_replace(",", ".", $row[8])) - floatval(str_replace(",", ".", $row[20])),
                    ]
                );

                if ($row[36] != null) {
                    $good->storeImageByUrl($row[36], 1);
                }
                else {
                    $good->deleteImageByOrder(1);
                }
        
                if ($row[37] != null) {
                    $links = explode(', ', $row[37]);
                    foreach($links as $key => $link) {
                        $good->storeImageByUrl($link, $key + 2);
                    }
                    $images_number = count($links) + 1;
                    $good_images_number = $good->images()->count();
                    if ($good_images_number > $images_number) {
                        for ($i = $good_images_number; $i > $images_number; $i--) {
                            $good->deleteImageByOrder($i);
                        }
                    }
                }
            }

            if ($rowIndex == 1) {
                for ($i = 31; $i < count($row); $i++) {
                    if ($i == 36 || $i == 37) {
                        continue;
                    }

                    $this->additionalAttributeHeaders[$i] = mb_strtolower(str_replace("Доп. поле: ", "", $row[$i]));
                }
            } else {
                for ($i = 31; $i < count($row); $i++) {
                    if ($i == 36 || $i == 37) {
                        continue;
                    }
                    $key = $this->additionalAttributeHeaders[$i];

                    if ($row[$i] != null) {
                        $good->attributes()->updateOrCreate(
                            ['good_id' => $good->id, 'key' => $key],
                            ['value' => $row[$i]]
                        );
                    }
                    else {
                        $attribute = $good->attributes()->where('key', $key);

                        if ($attribute->exists()) {
                            $attribute->delete();
                        }
                    }
                }
            }
        });
    }
}
