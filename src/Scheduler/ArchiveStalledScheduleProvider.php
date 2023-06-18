<?php

namespace App\Scheduler;

use App\Entity\Article;
use App\Message\ArchiveStalledArticles;
use App\Repository\ArticleRepository;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('stalled_articles')]
class ArchiveStalledScheduleProvider implements ScheduleProviderInterface
{
    public function __construct(
        private readonly ArticleRepository $articleRepository
    ) {}

    public function getSchedule(): Schedule
    {
        return (new Schedule())->add(
            RecurringMessage::every(
                '1 day',
                new ArchiveStalledArticles(
                    $this->articleRepository->findBy(['status' => Article::TO_READ ])
                )
            )
        );
    }
}
