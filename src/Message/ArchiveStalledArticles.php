<?php

namespace App\Message;

final class ArchiveStalledArticles
{
     public function __construct(
         public array $articles
     ) {}
}
