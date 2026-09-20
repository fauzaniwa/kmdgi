<?php

use Illuminate\Support\Facades\Broadcast;

// User hanya boleh mendengarkan (subscribe) ke notifikasi miliknya sendiri
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});