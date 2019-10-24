<?php

namespace Baloot\Tests;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use Baloot\EloquentHelper;

class BasicTest extends TestCase
{
    public function testEnToFa()
    {
        $this->assertEquals(en_to_fa('1234567890'), '۱۲۳۴۵۶۷۸۹۰');
    }

    public function testFaToEn()
    {
        $this->assertEquals('۱۲۳۴۵۶۷۸۹۰', en_to_fa('1234567890'));
    }

    public function testMiddleware()
    {
        $request = new Request();

        $request->merge([
            'test_fa' => '۱۲۳۴۵۶۷۸۹۰',
            'test_arabic' => 'ك,ي',
            'arr' => [
                'test_fa' => '۱۲۳۴۵۶۷۸۹۰',
                'test_arabic' => 'ك,ي',
                'arr2' => [
                    'test_fa' => '۱۲۳۴۵۶۷۸۹۰',
                    'test_arabic' => 'ك,ي'
                ]
            ]
        ]);

        $middleware = new \Baloot\Middleware\FixRequestInputs;

        $middleware->handle($request, function ($req) {
            $this->assertEquals($req['test_fa'], '1234567890');
            $this->assertEquals($req['test_arabic'], 'ک,ی');
            $this->assertEquals($req['arr']['test_fa'], '1234567890');
            $this->assertEquals($req['arr']['test_arabic'], 'ک,ی');
            $this->assertEquals($req['arr']['arr2']['test_fa'], '1234567890');
            $this->assertEquals($req['arr']['arr2']['test_arabic'], 'ک,ی');
        });
    }

    public function testStrToSlug()
    {
        $this->assertEquals('helloworld', str_to_slug('helloworld'));
        $this->assertEquals('hello-world', str_to_slug('hello world'));
        $this->assertEquals('hello-world', str_to_slug('hello world '));
        $this->assertEquals('hello-world', str_to_slug(' hello world'));
        $this->assertEquals('سلام', str_to_slug(' سلام'));
        $this->assertEquals('سلام-دنیا', str_to_slug(' سلام دنیا'));
    }

    public function testFindBankByCardNumber()
    {
        $this->assertEquals('bsi', find_bank_by_card_number('6037697531')['class']);
        $this->assertEquals(null, find_bank_by_card_number('12345678'));
    }

    public function testEloquentHelper()
    {
        Artisan::call('migrate');
        $model = new class extends Model {
            use EloquentHelper;

            protected $casts = [
                'birth_date' => 'date'
            ];
        };
        $model->setDateFormat('Y/j/d');
        // getter
        $model->birth_date = Carbon::createFromDate(2001, 12, 11);
        $this->assertEquals(verta($model->birth_date)->formatJalaliDate(), $model->birth_date_fa_f);
        $this->assertEquals(verta($model->birth_date)->format("Y/n/j H:i"), $model->birth_date_fa_ft);
        $this->assertEquals(verta($model->birth_date)->formatJalaliDateTime(), $model->birth_date_fa_ftt);

        // setter
        $model->birth_date_fa = "1370/1/1";
        $this->assertEquals($model->birth_date->format("Y-m-d"), "1991-10-21");

        // aparat
        $model->video = "https://www.aparat.com/v/O4qSP";
        $this->assertInstanceOf(\Baloot\Models\AparatVideo::class, $model->video_aparat);
        $this->assertEquals("ایستگاه جوانمرد راستگو", $model->video_aparat->title);
    }

    protected function getPackageProviders($app)
    {
        return [\Baloot\BalootServiceProvider::class];
    }
}
