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
    private $columnKeys = [];

    private $imageKeys = [];

    public function onRow(Row $row)
    {
        DB::transaction(function () use ($row) {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            if ($rowIndex == 1) {
                for ($i = 0; $i < count($row); $i++) {
                    if (mb_strpos($row[$i], 'Доп. поле:') !== false) {
                        if (mb_strpos($row[$i], 'Ссылка на упаковку') !== false) {
                            $this->imageKeys[0] = $i;
                        }
                        elseif (mb_strpos($row[$i], 'Ссылки на фото') !== false) {
                            $this->imageKeys[1] = $i;
                        }
                        else {
                            $this->additionalAttributeHeaders[$i] = mb_strtolower(str_replace("Доп. поле: ", "", $row[$i]));
                        }
                    }
                    else {
                        $this->columnKeys[$row[$i]] = $i;
                    }
                }
            } 
            elseif ($rowIndex != 1) {
                if ($row[$this->columnKeys['Внешний код']] == null) {
                    return;
                }

                $good = Good::updateOrCreate(
                    ['external_code' => $row[$this->columnKeys['Внешний код']]],
                    [
                        'name' => $row[$this->columnKeys['Наименование']],
                        'description' => $row[$this->columnKeys['Описание']],
                        'price' => floatval(str_replace(",", ".", $row[$this->columnKeys['Цена: Цена продажи']])),
                        'discount' => floatval(str_replace(",", ".", $row[$this->columnKeys['Цена: Цена продажи']])) - floatval(str_replace(",", ".", $row[$this->columnKeys['Минимальная цена']])),
                    ]
                );
                
                if ($row[$this->imageKeys[0]] != null) {
                    $good->storeImageByUrl($row[$this->imageKeys[0]], 1);
                }
                else {
                    $good->deleteImageByOrder(1);
                }
        
                if ($row[$this->imageKeys[1]] != null) {
                    $links = explode(', ', $row[$this->imageKeys[1]]);
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
                foreach($this->additionalAttributeHeaders as $key => $value) {
                    if ($row[$key] != null) {
                        $good->attributes()->updateOrCreate(
                            ['good_id' => $good->id, 'key' => $value],
                            ['value' => $row[$key]]
                        );
                    }
                    else {
                        $attribute = $good->attributes()->where('key', $value);

                        if ($attribute->exists()) {
                            $attribute->delete();
                        }
                    }
                }
            }
        });
    }
}
