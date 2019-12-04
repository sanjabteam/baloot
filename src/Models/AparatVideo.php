<?php

namespace Baloot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property string $title              عنوان ویدیو
 * @property string $username           نام کاربری فرستنده ویدیو
 * @property int $userid                آیدی فرستنده ویدیو
 * @property int $visit_cnt             تعداد بازدیدهای ویدیو
 * @property string $uid                uid یکتای ویدیو
 * @property string $process            وضعیت ویدیو
 * @property string $sender_name        نام فرستنده ویدیو
 * @property string $big_poster         لینک پوستر ویدیو سایز بزرگ
 * @property string $small_poster       لینک پوستر ویدیو سایز کوچک
 * @property string $profilePhoto       لینک عکس فرستنده ویدیو
 * @property string $duration           مدت زمان ویدیو به ثانیه
 * @property string $sdate              تاریخ ارسال ویدیو
 * @property string $frame              لینک frame ویدیو
 * @property string $official           رسمی یا نبودن کانال
 * @property array $tags                لیستی از تگ ها
 * @property string $description        توضیحات ویدیو
 * @property int $cat_id                آیدی طبقه بندی سایت ویدیو
 * @property string $cat_name           نام طبقه بندی ویدیو
 * @property bool $autoplay          اتوپلی ویدیو هست یا نه
 * @property bool $is_360d           ویدیو 360 درجه هست یا نه
 * @property string $has_comment        امکان نظر گذاشتن ویدیو
 * @property string $has_comment_txt    متن گذاشتن نظر برای ویدیو
 * @property int $size                  حجم ویدیو
 * @property bool $can_download      امکان دانلود ویدیو وجود دارد یا خیر
 * @property int $like_cnt              تعداد افرادی که این ویدیو رو پسندید
 */
class AparatVideo extends Model
{
    protected $fillable = [
        'title',
        'username',
        'userid',
        'visit_cnt',
        'uid',
        'process',
        'sender_name',
        'big_poster',
        'small_poster',
        'profilePhoto',
        'duration',
        'sdate',
        'frame',
        'official',
        'tags',
        'description',
        'cat_id',
        'cat_name',
        'autoplay',
        'is_360d',
        'has_comment',
        'has_comment_txt',
        'size',
        'can_download',
        'like_cnt',
    ];

    protected $casts = [
        'tags'         => 'array',
        'userid'       => 'int',
        'visit_cnt'    => 'int',
        'cat_id'       => 'int',
        'autoplay'     => 'bool',
        'is_360d'      => 'bool',
        'size'         => 'int',
        'can_download' => 'bool',
        'like_cnt'     => 'int',
    ];

    /**
     * بازخوانی مجدد اطلاعات از سرور آپارات.
     *
     * @return self
     */
    public function reload()
    {
        $data = Arr::first(aparat_info(['https://www.aparat.com/v/'.$this->uid], false));
        if ($data) {
            $this->fill($data);
            $this->save();
        }

        return $this;
    }
}
