<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Одна аптека в Албании
        Pharmacy::create([
            'name' => 'Farmaci Ditë e Natë Nr. 001 - Ushtari i Panjohur',
            'address' => 'Pll 1/4 Tirana, Albania, Rruga Luigj Gurakuqi',
            'latitude' => '41.328793047827105',
            'longitude' => '19.82248291534419',
            'opening_hours' => '07:00–03:00',
            'phone' => '+35544541237',
            'website' => 'https://www.google.com/maps/place/Farmaci+Dit%C3%AB+e+Nat%C3%AB+Nr.+001+-+Ushtari+i+Panjohur/@41.328644,19.82244,17z/data=!3m1!4b1!4m6!3m5!1s0x13503110e6c290e5:0x429246f8afcf5a8c!8m2!3d41.328644!4d19.82244!16s%2Fg%2F11ckkw_kkm?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        //Норвегия
        Pharmacy::create([
            'name' => 'Vitusapotek Aker Brygge',
            'address' => 'Støperigata 1, 0250 Oslo',
            'latitude' => '59.911357088450146',
            'longitude' => '10.724252199868962',
            'opening_hours' => '09:00–18:00',
            'phone' => '+4723115450',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Vitusapotek Nationaltheatret',
            'address' => 'Stortingsgata 30, 0161 Oslo',
            'latitude' => '41.328793047827105',
            'longitude' => '19.82248291534419',
            'opening_hours' => '07:00–03:00',
            'phone' => '+4722337007',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Ditt apotek Rådhuset AS',
            'address' => 'Fridtjof Nansens plass 5, 0160 Oslo',
            'latitude' => '59.91430880090438',
            'longitude' => '10.73465163738938',
            'opening_hours' => '08:00–19:00',
            'phone' => '+4791691346',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Apotek 1 St. Hanshaugen',
            'address' => 'Bjerregaards gate 2F, 0172 Oslo',
            'latitude' => '59.92392879475466',
            'longitude' => '10.740157221959011',
            'opening_hours' => '09:00–17:00',
            'phone' => '+4722591790',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Balder apotek',
            'address' => 'Munchs gate 7, 0165 Oslo',
            'latitude' => '59.9183334403863',
            'longitude' => '10.741380685196708',
            'opening_hours' => '08:30–17:00',
            'phone' => '+4722604001',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Apotek 1 Kronen',
            'address' => 'Grensen 9, 0159 Oslo',
            'latitude' => '59.91473045268719',
            'longitude' => '10.743521745862676',
            'opening_hours' => '09:00–18:00',
            'phone' => '+4722910390',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'BlueTag',
            'address' => 'Langkaia 1, 0150 Oslo',
            'latitude' => '59.90829009621724',
            'longitude' => '10.747268602042674',
            'opening_hours' => '08:30–17:00',
            'phone' => '+4722604001',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Vitusapotek Jernbanetorget',
            'address' => 'Jernbanetorget 4B, 0154 Oslo',
            'latitude' => '59.912200461657754',
            'longitude' => '10.749409662708642',
            'opening_hours' => 'Круглосуточно',
            'phone' => '+4723358100',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Apotek 1 Legevakten',
            'address' => 'Storgata 40, 0182 Oslo',
            'latitude' => '59.91760520846636',
            'longitude' => '10.75896796926106',
            'opening_hours' => '10:00–18:00',
            'phone' => '+4722988720',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);

        Pharmacy::create([
            'name' => 'Ditt Apotek Opera',
            'address' => 'Operagata 18, 0194 Oslo',
            'latitude' => '59.90829009615915',
            'longitude' => '10.756521042797402',
            'opening_hours' => '08:00–21:00',
            'phone' => '+4722332300',
            'website' => 'https://www.google.com/maps/search/%D0%B4%D0%B5%D0%B6%D1%83%D1%80%D0%BD%D1%8B%D0%B5+%D0%B0%D0%BF%D1%82%D0%B5%D0%BA%D0%B8+%D0%B3%D1%83%D0%B3%D0%BB+%D0%BA%D0%B0%D1%80%D1%82%D1%8B+%D0%BE%D1%81%D0%BB%D0%BE+%D0%BD%D0%BE%D1%80%D0%B2%D0%B5%D0%B3%D0%B8%D1%8F/@59.9121795,10.7307349,13.87z?entry=ttu&g_ep=EgoyMDI1MDQyOS4wIKXMDSoASAFQAw%3D%3D',
        ]);
    }
}
