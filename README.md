<div dir="rtl">

# بلوط
> پکیج لاراول مخصوص توسعه دهندگان ایرانی

من همیشه یه سری کد داشتم که هر بار پروژه جدید ایجاد میکردم هر بار از اول کپی میکردم تو پروژه جدیده.بعد تصمیم گرفتم این رو به صورت یه پکیج در بیارم تا هم خودم و هم همکارام به راحتی بتونیم ازشون استفاده کنیم.
حالا اگه شما هم فکر میکنین این پکیج میتونه روند توسعه نرم افزار هاتون رو سریع کنه شما هم استفاده کنین. اگه فکر میکنین چیز خاصی هست که مخصوص توسعه دهنده های ایرانی هست اما در این پکیج موجود نیست حتما یه

forkو pull request

مهمون کنین :).

<p align="center">

[![Build Status](https://img.shields.io/travis/sanjabteam/baloot/master.svg?style=flat-square)](https://travis-ci.org/sanjabteam/baloot)

</p>

امکانات:
* ذخیره و بازیابی تاریخ شمسی در الوکوئنت
* دسترسی به ای پی آی سایت آپارات مستقیما از مدل
* میدلویر برای تعمیر حروف ک و ی عربی و اعداد فارسی و عربی
* تابع تهیه اسلاگ فارسی
* امکانات اضافه شده به فیکر از جمله لینک ویدیو آپارات
* ولیدیتور شماره همراه و شماره تماس ثابت
* دیتابیس شهر ها و استان های ایران به همراه روت بایندینگ ها
* پیدا کردن بانک از روی شماره کارت

- [بلوط](#%d8%a8%d9%84%d9%88%d8%b7)
  - [نصب](#%d9%86%d8%b5%d8%a8)
  - [کانفیگ](#%da%a9%d8%a7%d9%86%d9%81%db%8c%da%af)
  - [ذخیره و بازیابی تاریخ شمسی](#%d8%b0%d8%ae%db%8c%d8%b1%d9%87-%d9%88-%d8%a8%d8%a7%d8%b2%db%8c%d8%a7%d8%a8%db%8c-%d8%aa%d8%a7%d8%b1%db%8c%d8%ae-%d8%b4%d9%85%d8%b3%db%8c)
    - [استفاده از اتریبیوت های دلخواه](#%d8%a7%d8%b3%d8%aa%d9%81%d8%a7%d8%af%d9%87-%d8%a7%d8%b2-%d8%a7%d8%aa%d8%b1%db%8c%d8%a8%db%8c%d9%88%d8%aa-%d9%87%d8%a7%db%8c-%d8%af%d9%84%d8%ae%d9%88%d8%a7%d9%87)
  - [مشخصات ویدیو آپارات](#%d9%85%d8%b4%d8%ae%d8%b5%d8%a7%d8%aa-%d9%88%db%8c%d8%af%db%8c%d9%88-%d8%a2%d9%be%d8%a7%d8%b1%d8%a7%d8%aa)
    - [نکته بسیار مهم](#%d9%86%da%a9%d8%aa%d9%87-%d8%a8%d8%b3%db%8c%d8%a7%d8%b1-%d9%85%d9%87%d9%85)
  - [میدلویر](#%d9%85%db%8c%d8%af%d9%84%d9%88%db%8c%d8%b1)
  - [ولیدیشن](#%d9%88%d9%84%db%8c%d8%af%db%8c%d8%b4%d9%86)
  - [فیکر](#%d9%81%db%8c%da%a9%d8%b1)
  - [استان ها و شهرها](#%d8%a7%d8%b3%d8%aa%d8%a7%d9%86-%d9%87%d8%a7-%d9%88-%d8%b4%d9%87%d8%b1%d9%87%d8%a7)
    - [روت بایندینگ](#%d8%b1%d9%88%d8%aa-%d8%a8%d8%a7%db%8c%d9%86%d8%af%db%8c%d9%86%da%af)
  - [اسلاگ](#%d8%a7%d8%b3%d9%84%d8%a7%da%af)
    - [استفاده با پکیج Eloquent Sluggable](#%d8%a7%d8%b3%d8%aa%d9%81%d8%a7%d8%af%d9%87-%d8%a8%d8%a7-%d9%be%da%a9%db%8c%d8%ac-eloquent-sluggable)
  - [پیداکردن بانک از روی شماره کارت](#%d9%be%db%8c%d8%af%d8%a7%da%a9%d8%b1%d8%af%d9%86-%d8%a8%d8%a7%d9%86%da%a9-%d8%a7%d8%b2-%d8%b1%d9%88%db%8c-%d8%b4%d9%85%d8%a7%d8%b1%d9%87-%da%a9%d8%a7%d8%b1%d8%aa)
  - [ساخته شده با کمک](#%d8%b3%d8%a7%d8%ae%d8%aa%d9%87-%d8%b4%d8%af%d9%87-%d8%a8%d8%a7-%da%a9%d9%85%da%a9)

## نصب
برای نصب شما به لاراول نسخه 6 یا بالاتر نیاز دارید. با استفاده از کومپوزر در پروژه لاراولی خود این پکیج رو نصب کنید.
<div dir="ltr">

```bash
composer require sanjabteam/baloot
```

</div>

بعد هم با این کامند فایل کانفیگ رو بسازین

<div dir="ltr">

```bash
php artisan vendor:publish --provider=Baloot\\BalootServiceProvider
```

</div>

## کانفیگ
در فایل
<div dir="ltr">

```
config/baloot.php
```

</div>

میتونین کانفیگ رو تغییر بدین.

`geo`:
اگه نمیخاین از قابلیت استان و شهر استفاده کنین مقدار این قسمت رو برابر

`false`

قرار بدید تا جداولش ایجاد نشه.

`aparat`:
اگه نمیخاین از قابلیت آپارات استفاده کنین مقدار این قسمت رو برابر

`false`

قرار بدید تا جداولش ایجاد نشه.

## ذخیره و بازیابی تاریخ شمسی
این پکیج این امکان رو به شما میده تا به راحتی تاریخ های موجود در مدل لاراول رو به راحتی به کلاس [ورتا](https://github.com/hekmatinasser/verta) تبدیل کنید.
برای شروع

trait

`Baloot\EloquentHelper`

رو به مدلی که میخاین اضافه کنین.

<div dir="ltr">

```php
use Baloot\EloquentHelper;

class User extends Model
{
    use EloquentHelper; // trait
}
```

</div>

بعد به راحتی میتونین به صورت شمسی به مشخصات دسترسی داشته باشین. فقط کافیه به فیلد مورد نظرتون پسوند

`_fa`

اضافه کنید.

<div dir="ltr">

```php
$user = User::where(...)->first();
$user->created_at_fa // به صورت کلاس Hekmatinasser\Verta\Verta
$user->created_at_fa_f // 1390/1/1
$user->created_at_fa_ft // 1390/1/1 12:00
$user->created_at_fa_ftt // 1390/1/1 12:00:00
$user->updated_at_fa->format("%B %d %Y") // فروردین 01 1390
```

</div>

برای مدیریت بهتر میتونین به مستندات
[ورتا](https://github.com/hekmatinasser/verta)
.مراجعه کنین
همچنین با تنظیم کردن تاریخ هم از همین روش استفاده کنین.

<div dir="ltr">

```php
$user->created_at_fa = Verta::createJalaliDate(1390, 1, 1);
dd($user->created_at); // Illuminate\Support\Carbon { date: 2011-03-21 }
$user->created_at_fa = "1392/1/1";
dd($user->created_at); // Illuminate\Support\Carbon { date: 2013-03-21 }
$user->created_at_fa = "1395/1/1 14:22:11";
dd($user->created_at); // Illuminate\Support\Carbon { date: 2016-03-20 14:22:11 }
```

</div>

### استفاده از اتریبیوت های دلخواه
اگه میخاین یه فیلد دلخواه به جز

created_at, updated_at

داشته باشین که از همین قابلیت پشتیبانی کنه کافیه اون رو به تاریخ [کست](https://laravel.com/docs/6.x/eloquent-mutators#date-casting) کنین.

<div dir="ltr">

```php
use Baloot\EloquentHelper;

class User extends Model
{
    use EloquentHelper;

    protected $casts = [
        'birth_date' => 'date',
        // یا
        'released_at' => 'datetime'
    ];
}
```

</div>

و بازم هم به همون روش میتونین بهش دسترسی داشته باشین.

<div dir="ltr">

```php
$user->birth_date_fa; // Hekmatinasser\Verta\Verta
```

</div>

## مشخصات ویدیو آپارات
این پکیج این امکان رو به شما میده تا به راحتی مشخصات یه فیلم رو از [ای پی آی آپارات](https://aparat.com/api) از طریق آدرس فیلم دریافت کنین.
برای شروع

trait

`Baloot\EloquentHelper`

رو به مدلی که میخاین اضافه کنین.


<div dir="ltr">

```php
use Baloot\EloquentHelper;

class Post extends Model
{
    use EloquentHelper; // trait
}
```

</div>

بعد فقط کافیه که آدرس فیلم رو درون یه فیلد ذخیره کنین. بعد با پسوند

`_aparat`

به مشخصاتش دسترسی پیدا کنین.

<div dir="ltr">

```php
$post = Post::first();
$post->video = "https://www.aparat.com/v/O4qSP";
dd($post->video_aparat); // Baloot\Models\AparatVideo
dd($post->video_aparat->title); // ایستگاه جوانمرد راستگو
dd($post->video_aparat->username); // ding.dong
dd($post->video_aparat->sender_name); //دینگ دانگ
```

</div>

اگه میخاین مستقیم و بدون مدل به مشخصات دسترسی داشته باشین از این تابع استفاده کنین.


<div dir="ltr">

```php
aparat_info(["https://www.aparat.com/v/O4qSP"]);
```

</div>

مشخصات کامل: [AparatVideo](./src/Models/AparatVideo.php)


### نکته بسیار مهم
آپارات اگه طی یه زمان از حدی بیشتر بهش درخواست بفرسین یه ارور

429 Too Many Requests

بهتون تحویل میده و حسابی دردسر درست میکنه براتون برای همین من مشخصات فیلم ها داخل دیتابیس ذخیره میکنم بنابراین مشخصات فیلم تا زمانی که خودتون نخواید تغییری نمیکنه. یه سری چیز ها مثل تعداد بازدید و تعداد لایک خیلی سریع تغییر میکنن اما به نظرم ارزش اینو ندارن که عاقبتتون 429 بشه.
برای بروز کردن مشخصات فیلم کافیه که از این روش استفاده کنین.

<div dir="ltr">

```php
$post->video_aparat->visit_cnt; // 26550
$post->video_aparat->reload();
$post->video_aparat->visit_cnt; // 26812
```

</div>

## میدلویر
یکی از مشکلاتی که تو پروژه ها سر و کله میزنم باهاشون یکی اعداد فارسی هست. مثلا طرف میاد موقع ثبت نام رمز عبورش رو با عدد فارسی میزنه بعد موقع ورود با عدد انگلیسی بعد این وسط میگه رمز عبورش اشتباهه درصورتی که اینطوری نیست.
مشکل دوم اینه که مثلا ادمین یه چیزی با ک و ی فارسی وارد میکنه اما یه کاربری تو سایت کیبوردش عربی و با ك,ي عربی سرچ میکنه و این وسط موقع سرچ چیزی پیدا نمیکنه در صورتی که اینطور نیستش.
برای حل این مشکل کافیه در فایل

`app/Http/Kernel.php`

کلاس

<div dir="ltr">

```php
\Baloot\Middleware\FixRequestInputs
```

</div>

به آرایه

middleware

اضافه کنین

<div dir="ltr">

```php
protected $middleware = [
    ...
    \Baloot\Middleware\FixRequestInputs::class,
];
```

</div>

به همین راحتی برای همیشه هم با مشکل اعداد فارسی و هم مشکل حروف عربی خداحافظی کنین.


## ولیدیشن
iran_phone:
برای وارد کردن شماره های ثابت ایرانی

iran_mobile:
برای ولیدیشن شماره موبایل های ایرانی

برای استفاده:

<div dir="ltr">

```php
public function test(Request $request)
{
    $request->validate([
        'test1' => 'required|iran_mobile',
        'test2' => 'required|iran_phone',
    ]);
}
```

</div>

> این قسمت نیاز به بهبود دارد

## فیکر
برای فیکر یه سری بهبود ها انجام شده برای مثال
paragraph
فارسی سازی شده.

CustomImage:

<div dir="ltr">

```php
customImage($path, $width, $height, $prefix)
```

</div>

این فیکر عکس از سایت

https://picsum.photos

براتون فراهم میکنه.

$path: پوشه محل ذخیره
$width: طول عکس
$height: عرض عکس
$prefix: به طور پیشفرض فقط نام عکس بهتون داده میشه با کمک این میتونین یه پیشوند به اسم عکس اضافه کنین

نمونه:

<div dir="ltr">

```php
'image' => $faker->customImage(public_path('uploads/fake'), 640, 480, 'fake/')
```

</div>

همچنین اگه میخاین یه آرایه از عکس داشته باشین

<div dir="ltr">

```php
$faker->customImages(public_path('uploads/fake'), 640, 480, $faker->numberBetween(1, 3), 'fake/')
```

</div>

چهارمین پارامتر تعداد عکس هایی که لازم دارین رو ازتون دریافت میکنه.

iranMobile:

یه شماره موبایل ایرانی براتون میسازه

<div dir="ltr">

```php
$faker->iranMobile
```

</div>

iranPhone:

یه شماره موبایل ایرانی براتون میسازه

<div dir="ltr">

```php
$faker->iranPhone
```

</div>

aparatVideo:

آدرس یه فیلم در آپارات به فراهم میکنه.


<div dir="ltr">

```php
$faker->aparatVideo
```

</div>

اگه آرایه ای از فیلم ها میخاید.


<div dir="ltr">

```php
$faker->aparatVideos($faker->numberBetween(1, 3))
```

</div>

پارامتر تعداد فیلم هایی که لازم دارین رو ازتون دریافت میکنه.

## استان ها و شهرها
برای شروع در

`DatabaseSeeder.php`

این قسمت رو اضافه کنید.


<div dir="ltr">

```php
public function run()
{
    $this->call(\Baloot\Database\CitiesTableSeeder::class); // سیدر شهر ها و استان ها
}
```

</div>

سپس بعد از میگریت سید رو انجام بدین.
```bash
php artisan migrate --seed
```
حالا جداول شما از شهر ها و استان ها پر شده برای استفاده از دو مدل پایین میتونین استفاده کنین.

`Baloot\Models\Province`:مدل استان

`Baloot\Models\City`:مدل شهر

نمونه:

<div dir="ltr">

```php
use anjabHelpers\Models\City;

City::where('name', 'آمل')->first()
```

</div>

### روت بایندینگ
اگه میخاین از استان و شهر در آدرس ها استفاده کنین از این روش استفاده کنین.


<div dir="ltr">

```php
use Baloot\Models\Province;
use anjabHelpers\Models\City;

Route::get('test/{province}/{city}', function (Province $province, City $city) {
    abort_if($city->province_id != $province->id, 404);
    // ...
});
```

</div>

و سپس آدرس زیر رو باز کنید.

`/test/27/1068`

حالا به استان و شهر دسترسی دارین.

اگه میخاین آدرستون

seo freindly

باشه از

slug

موجود در شهر ها و استان ها استفاده کنین.
این اسلاگ ها به کمک

[Eloquent Sluggable](https://github.com/cviebrock/eloquent-sluggable)

تهیه شدن.
بنابراین آدرس رو به این شکل باز کنین.

`/test/مازندران/آمل`.

اگه فقط میخاین با آی دی باز باشه یا فقط اسلاگ و هر دو حالت رو قبول نکنه در اون صورت از این ها به جای

`province`, `city`

استفاده کنین.

`city_by_id`: شهر با آی دی

`city_by_slug`: شهر با اسلاگ

`province_by_id`: استان با آی دی

`province_by_slug`: استان با اسلاگ


استان: [Province](./src/Models/Province.php)

شهر: [City](./src/Models/City.php)

## اسلاگ
همونطور که میدونین در لاراول با تابع


<div dir="ltr">

```php
Str::slug('test test')
```

</div>

میشه یه اسلاگ برای آدرس دهی درست کرد اما اگه فارسی به این تابع بدین

<div dir="ltr">

```php
Str::slug('خونه ی مادربزرگه')
```

</div>

خروجی

`"khonh-i-madrbzrgh"`

میده که یه جورایی سعی کرده به فینگلیش تبدیلش کنه اما با تابع
`str_to_slug`
این پکیج به راحتی حروف فارسی رو هم مدیریت میکنه

<div dir="ltr">

```php
str_to_slug('خونه ی مادربزرگه')
```

</div>

`"خونه-ی-مادربزرگه"`

### استفاده با پکیج Eloquent Sluggable
کافیه در فایل

`config/sluggable.php`

قسمت

method

رو این شکلی بنویسین.


<div dir="ltr">

```php
'method' => 'str_to_slug',
```

</div>

## پیداکردن بانک از روی شماره کارت
برای پیدا کردن بانک از روی شماره کارت از این تابع استفاده کنین.


<div dir="ltr">

```php
find_bank_by_card_number("6037697531")
```

</div>

خروجی:

<div dir="ltr">

```php
[
    "class" => "bsi",
    "name" => "بانک صادرات ایران",
    "card_prefix" => "603769",
]
```

</div>

کلاس, نام کلاس بر اساس [این پکیج](https://github.com/webdesigniran/IranianBankLogos) هست.



## ساخته شده با کمک

- [Eloquent Sluggable](https://github.com/cviebrock/eloquent-sluggable)
- [ّIran Cities](https://github.com/ahmadazizi/iran-cities)
- [TestBench](https://github.com/orchestral/testbench)
- [Verta](https://github.com/hekmatinasser/verta)

</>
