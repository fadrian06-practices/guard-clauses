<?php

namespace App\Domain\Services;

use App\Domain\Models\NotificationIsNotAvailable;
use App\Domain\Models\UserId;
use App\Domain\Models\UserIsNotReady;

final readonly class SendBirthdayGreetingService
{
  const NOTIFICATION_NOT_SENT = 0;
  const NOTIFICATION_SUCCESSFULLY_SENT = 1;

  public function __construct(
    private NotificationAvailabilityChecker $notificationAvailabilityChecker,
    private NotificationSender $notificationSender
  ) {}

  public function __invoke(UserId $userId): int
  {
    if (!$this->isUserReady($userId)) {
      throw new UserIsNotReady;
    }

    if (!$this->notificationAvailabilityChecker->__invoke()) {
      $this->recordNotificationAvailabilityError();

      throw new NotificationIsNotAvailable;
    }

    if (!$this->isTheRightMomentToNotifyThisUser($userId)) {
      return self::NOTIFICATION_NOT_SENT;
    }

    $notificationContent = $this->calculateNotificationContent($userId);
    $this->notificationSender->__invoke($notificationContent);

    return self::NOTIFICATION_SUCCESSFULLY_SENT;
  }

  private function isUserReady(UserId $userId): bool
  {
    return true;
  }

  private function isTheRightMomentToNotifyThisUser(UserId $userId): bool
  {
    return true;
  }

  private function calculateNotificationContent(UserId $userId): mixed
  {
    return null;
  }

  private function recordNotificationAvailabilityError(): void {}
}
