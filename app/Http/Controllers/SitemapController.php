<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Sitemap XML untuk halaman publik.
 *
 * Sengaja dibangun dari daftar route, bukan file statis, supaya URL tidak
 * pernah basi saat struktur route berubah. Halaman privat (dashboard, hasil
 * kalkulator per-token, login) tidak pernah masuk ke sini.
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $pages = [
            ['route' => 'home',             'changefreq' => 'weekly',  'priority' => '1.0'],
            ['route' => 'kalkulator.polri', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['route' => 'program.kedinasan','changefreq' => 'monthly', 'priority' => '0.9'],
            ['route' => 'program.kebugaran','changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'program.atlet',    'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'daftar',           'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        $lastmod = now()->toAtomString();

        $xml = view('sitemap', compact('pages', 'lastmod'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
