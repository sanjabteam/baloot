<?php

namespace Baloot\Tests;

use Carbon\Carbon;
use Baloot\EloquentHelper;
use Baloot\Models\City;
use Baloot\Models\Province;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class BasicTest extends TestCase
{
    use DatabaseMigrations;

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
                    'test_arabic' => 'ك,ي',
                ],
            ],
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
                'birth_date' => 'date',
                'birth_date_fake' => 'date'
            ];
        };
        $model->setDateFormat('Y/j/d');
        // getter
        $model->birth_date = Carbon::createFromDate(2001, 12, 11);
        $this->assertEquals(verta($model->birth_date)->formatJalaliDate(), $model->birth_date_fa_f);
        $this->assertEquals(verta($model->birth_date)->format('Y/n/j H:i'), $model->birth_date_fa_ft);
        $this->assertEquals(verta($model->birth_date)->formatJalaliDateTime(), $model->birth_date_fa_ftt);

        // setter
        $model->birth_date_fa = '1370/1/1';
        $this->assertEquals($model->birth_date->format('Y-m-d'), '1991-12-21');

        // non-valid data
        $model->birth_date_fake_fa = '1370/1/1/1/1';
        $this->assertNull($model->birth_date_fake);

        // aparat
        $model->video = 'https://www.aparat.com/v/O4qSP';
        if ($model->video_aparat instanceof \Baloot\Models\AparatVideo) {
            $this->assertEquals('دینگ دانگ - ایستگاه جوانمرد راستگو', $model->video_aparat->title);
            $this->assertEquals($model->video_aparat->id, $model->video_aparat->reload()->id);
        }

        // Test aparat array
        $model->videos = ['https://www.aparat.com/v/O4qSP', 'https://www.aparat.com/v/6ARN8'];
        if (is_array($model->videos_aparat) &&
            count($model->videos_aparat) == 2 &&
            $model->videos_aparat[0] instanceof \Baloot\Models\AparatVideo &&
            $model->videos_aparat[1] instanceof \Baloot\Models\AparatVideo
        ) {
            $this->assertEquals('دینگ دانگ - ایستگاه جوانمرد راستگو', $model->videos_aparat[0]->title);
            $this->assertEquals('اولین تریلر رسمی فیلم The Grudge -  زومجی', $model->videos_aparat[1]->title);
        }
    }

    public function testFakerProvider()
    {
        $faker = app(\Faker\Generator::class);
        $this->assertIsString($faker->word);
        $this->assertIsString($faker->sentence);
        $this->assertIsString($faker->paragraph);
        $this->assertTrue(preg_match('/https:\/\/www.aparat.com\/v\/.+/', $faker->aparatVideo) > 0);
        $this->assertTrue(preg_match('/https:\/\/www.aparat.com\/v\/.+/', $faker->aparatVideos(2)[0]) > 0);
        $this->assertTrue(preg_match('/https:\/\/www.aparat.com\/v\/.+/', $faker->aparatVideos(2)[1]) > 0);
        $this->assertTrue(preg_match('/09\d+/', $faker->iranMobile) > 0);
        $this->assertTrue(preg_match('/0\d+/', $faker->iranPhone) > 0);

        $tempFolder = 'temp_'.time();
        $this->assertIsString($newFilePath = $faker->customImage(public_path($tempFolder), 32, 32, $tempFolder.'/'));
        $this->assertTrue(file_exists(public_path($newFilePath)));

        $this->assertISArray($newFilesPath = $faker->customImages(public_path($tempFolder), 32, 32, 2, $tempFolder.'/'));
        $this->assertCount(2, $newFilesPath);
        $this->assertIsString($newFilesPath[0]);
        $this->assertIsString($newFilesPath[1]);
        $this->assertTrue(file_exists(public_path($newFilesPath[0])));
        $this->assertTrue(file_exists(public_path($newFilesPath[1])));
    }

    public function testValidationRules()
    {
        $this->assertTrue(Validator::make(['mobile' => '09371234567'], ['mobile' => 'iran_mobile'])->passes());
        $this->assertFalse(Validator::make(['mobile' => '09371234'], ['mobile' => 'iran_mobile'])->passes());
        $this->assertFalse(Validator::make(['mobile' => '09371234567aaa'], ['mobile' => 'iran_mobile'])->passes());
        $this->assertFalse(Validator::make(['mobile' => 'aaa09371234567'], ['mobile' => 'iran_mobile'])->passes());
        $this->assertFalse(Validator::make(['mobile' => '0937123456789'], ['mobile' => 'iran_mobile'])->passes());

        $this->assertTrue(Validator::make(['phone' => '01112345678'], ['phone' => 'iran_phone'])->passes());
        $this->assertFalse(Validator::make(['phone' => '01112345'], ['phone' => 'iran_phone'])->passes());
        $this->assertFalse(Validator::make(['phone' => '01112345678aaa'], ['phone' => 'iran_phone'])->passes());
        $this->assertFalse(Validator::make(['phone' => 'aaa01112345678'], ['phone' => 'iran_phone'])->passes());
        $this->assertFalse(Validator::make(['phone' => '0111234567891'], ['phone' => 'iran_phone'])->passes());
    }

    public function testRouteBindings()
    {
        $this->app['router']->middleware('web')->group(function ($router) {
            $router->get('/province/{province}', function ($province) {
                return $province->id;
            });
            $router->get('/province_by_slug/{province_by_slug}', function (Province $province) {
                return $province->id;
            });
            $router->get('/province_by_id/{province_by_id}', function (Province $province) {
                return $province->id;
            });

            $router->get('/city/{city}', function (City $city) {
                return $city->id;
            });
            $router->get('/city_by_slug/{city_by_slug}', function (City $city) {
                return $city->id;
            });
            $router->get('/city_by_id/{city_by_id}', function (City $city) {
                return $city->id;
            });
        });

        $province = Province::inRandomOrder()->first();
        $city  = City::inRandomOrder()->first();
        $this->get('/province/'.$province->id)->assertStatus(200)->assertSee($province->id);
        $this->get('/province/'.$province->slug)->assertStatus(200)->assertSee($province->id);
        $this->get('/province_by_id/'.$province->id)->assertStatus(200)->assertSee($province->id);
        $this->get('/province_by_slug/'.$province->slug)->assertStatus(200)->assertSee($province->id);
        $this->get('/province/random-stuff')->assertStatus(404);

        $this->get('/city/'.$city->id)->assertStatus(200)->assertSee($city->id);
        $this->get('/city/'.$city->slug)->assertStatus(200)->assertSee($city->id);
        $this->get('/city_by_id/'.$city->id)->assertStatus(200)->assertSee($city->id);
        $this->get('/city_by_slug/'.$city->slug)->assertStatus(200)->assertSee($city->id);
        $this->get('/city/random-stuff')->assertStatus(404);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('vendor:publish', ['--provider' => 'Cviebrock\\EloquentSluggable\\ServiceProvider'])->run();
        $this->runDatabaseMigrations();
        $this->seed(\Baloot\Database\CitiesTableSeeder::class);
    }


    protected function getPackageProviders($app)
    {
        return [\Cviebrock\EloquentSluggable\ServiceProvider::class, \Baloot\BalootServiceProvider::class];
    }
}
