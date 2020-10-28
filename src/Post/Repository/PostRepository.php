<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Post\Repository;

use App\Post\Model\Post;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class PostRepository
{
    /**
     * @return Post[]
     */
    public function findAll(): array
    {
        return [
            new Post('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nibh nulla, rhoncus sed est nec, viverra rhoncus massa. Maecenas eros velit, mollis quis mi quis, finibus mollis metus. Curabitur et blandit ante, at aliquet ipsum. In metus elit, ullamcorper in consequat ac, facilisis at leo. Vestibulum vel justo at est commodo semper auctor eget lorem. Donec rutrum ante ut libero dignissim, eu ullamcorper eros dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec aliquet nibh justo. Vivamus semper pharetra pellentesque. In eget bibendum magna. Nunc at tellus non diam gravida varius ut pellentesque nunc. Nam id sem a nunc ultrices placerat vitae sit amet mi. Curabitur accumsan eros ac porttitor ullamcorper. '),
            new Post('Aenean sagittis placerat odio, vitae dictum augue accumsan at. Suspendisse sed rhoncus turpis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin quis euismod libero. Nullam placerat, augue sed consequat lacinia, orci arcu tristique urna, quis tincidunt turpis purus ut ex. Maecenas elementum justo eget augue finibus lobortis. Quisque volutpat convallis tellus sed gravida. Aenean venenatis a tortor vitae volutpat. Proin mollis pharetra dui non vulputate. In quis mauris ac sapien egestas interdum. Morbi sit amet ultricies turpis, sit amet volutpat dolor. Ut dapibus lacus eu ex scelerisque egestas. Etiam ultricies maximus elit, non finibus leo molestie sit amet. Nullam ac quam finibus, hendrerit massa sit amet, semper neque. Nunc condimentum posuere pellentesque. Sed faucibus nisi pharetra, vulputate leo vel, fringilla ligula. '),
            new Post('Fusce faucibus, elit at laoreet condimentum, quam diam congue neque, a suscipit enim mi vel enim. Morbi condimentum leo nec tincidunt rutrum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum volutpat, velit sit amet condimentum blandit, sem tortor posuere arcu, at maximus dolor arcu nec velit. Aliquam porttitor fringilla ipsum in hendrerit. Cras laoreet tellus et odio egestas, et dictum ipsum placerat. Donec vel lorem nec nibh porta aliquam nec eu purus. '),
            new Post('Nunc ornare pellentesque diam, sed interdum ex lobortis eu. Duis blandit nisi non tellus viverra volutpat. Sed erat arcu, ultricies quis eleifend non, finibus nec nisi. Duis nibh nisi, sagittis ut urna eget, tempor bibendum mi. Vestibulum tempus augue dignissim tortor ultricies volutpat. Sed massa dolor, posuere aliquam gravida eget, volutpat eget massa. Sed pretium rhoncus nibh nec tincidunt. Sed nec ante nibh. Vestibulum vestibulum, mi eget vehicula molestie, libero lacus cursus nisl, id luctus ligula ante in mauris. Ut aliquet neque nibh, sed fringilla enim ullamcorper ut. Aliquam lacinia risus ac erat rutrum condimentum. '),
            new Post('Vivamus imperdiet felis viverra, tristique dui sed, pulvinar nibh. Nam euismod venenatis tempor. Vivamus a augue bibendum erat accumsan condimentum. Nam nec ante risus. Donec rhoncus libero non placerat facilisis. Etiam pharetra porta dui ut faucibus. Ut rutrum elit arcu, quis sagittis ipsum luctus et. '),
        ];
    }

    public function findAllPaginated(): Pagerfanta
    {
        return new Pagerfanta(new ArrayAdapter($this->findAll()));
    }
}
