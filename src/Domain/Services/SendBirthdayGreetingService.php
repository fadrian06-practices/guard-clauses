<?php

namespace App\Domain\Services;

use App\Domain\Models\NotificationIsNotAvailable;
use App\Domain\Models\UserId;
use App\Domain\Models\UserIsNotReady;

final class SendBirthdayGreetingService
{
  const NOTIFICATION_NOT_SENT = 0;
  const NOTIFICATION_SUCCESSFULLY_SENT = 1;

  private $notificationAvailabilityChecker;
  private $notificationSender;

  public function __construct(
    NotificationAvailabilityChecker $aNotificationAvailabilityChecker,
    NotificationSender $aNotificationSender
  ) {
    $this->notificationAvailabilityChecker = $aNotificationAvailabilityChecker;
    $this->notificationSender = $aNotificationSender;
  }

  public function __invoke(UserId $userId): int
  {
    if ($this->isUserReady($userId)) {
      if ($this->notificationAvailabilityChecker->__invoke()) {
        if ($this->isTheRightMomentToNotifyThisUser($userId)) {
          $notificationContent = $this->calculateNotificationContent($userId);
          $this->notificationSender->__invoke($notificationContent);

          return self::NOTIFICATION_SUCCESSFULLY_SENT;
        } else {
          return self::NOTIFICATION_NOT_SENT;
        }
      } else {
        $this->recordNotificationAvailabilityError();

        throw new NotificationIsNotAvailable;
      }
    } else {
      throw new UserIsNotReady;
    }
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

  private function recordNotificationAvailabilityError(): void
  {
  }
}
