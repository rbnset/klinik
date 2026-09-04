<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    /**
     * @var view-string
     */
    protected string $view = 'filament.admin.pages.auth.login';

    public function getTitle(): string | Htmlable
    {
        return 'Masuk — Praktek Bidan Puji Susanti';
    }

    public function getHeading(): string | Htmlable | null
    {
        return 'Selamat datang kembali';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return new HtmlString('Masuk untuk mengelola pengadaan dan persediaan secara terstruktur.');
    }
}
