<?php

namespace Tests\Browser;

use App\User;
use App\Address;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\IndexComponent;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScoutSearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function resources_can_be_searched()
    {
        $this->seed();

        factory(Address::class, 50)->create();

        $address = Address::find(random_int(1, 50));

        $this->browse(function (Browser $browser) use ($address) {
            $browser->loginAs(User::find(1))
                    ->visit(new Pages\Index('addresses'))
                    ->within(new IndexComponent('addresses'), function ($browser) use ($address) {
                        $browser->searchFor($address->address_line_1);
                    })
                    ->assertSee($address->address_line_1)
                    ->assertSee($address->city);
        });
    }
}