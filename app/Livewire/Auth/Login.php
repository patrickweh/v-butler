<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $this->validate();

        return redirect()->route('login', ['password' => $this->password, 'email' => $this->email]);
    }
}
