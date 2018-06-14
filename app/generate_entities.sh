#!/usr/bin/env bash
php bin/console doctrine:mapping:import AppBundle annotation --force
php bin/console doctrine:generate:entities AppBundle