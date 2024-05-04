<?php

namespace App\Enum;

class AccountApplicationStatus
{
  const ACTIVATING = 'Activating';
  const SETTINGS_REQUIRED = 'SettingsRequired';
  const ACTIVATED = 'Activated';
  const ACTIVATION_FAILED = 'ActivationFailed';
  const UNINSTALLED = 'Uninstaled';
  const SUSPEND = 'Suspend';
  const ERROR = 'Error';
}