<?php

use App\Models\User;
use App\Models\WebsiteDetail;

it('user belongs to website detail', function () {
    $websiteDetail = WebsiteDetail::factory()->create();
    $user = User::factory()->create(['website_detail_id' => $websiteDetail->id]);

    expect($user->website_detail_id)->toBe($websiteDetail->id);
    expect($websiteDetail->users[0]->id)->toBe($user->id);
});
