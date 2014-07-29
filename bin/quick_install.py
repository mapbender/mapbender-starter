#!/usr/bin/env python
"""Quickly install Mapbender3 starter using Git.

You can just call it or give the name of a branch (defaults to 'develop' as
well as a the directory where to install/update (defaults to
mapbender3_BRANCH).

The default admin account (root <root@example.com> / root) can also be given
using command line arguments. Check the help with -h.

Examples
========

http://bit.ly/1tQvo5i is the shortened URL for
https://raw.githubusercontent.com/mapbender/mapbender-starter/develop/bin/quick_install.py

- Install develop branch into mapbender3_develop

    curl -sSL http://bit.ly/1tQvo5i | python

- Install foo branch into /tmp/bar

    curl -sSL http://bit.ly/1tQvo5i | python - --dir=/tmp/bar foo

- Install develop branch, but use admin <admin@example.com> with password admin

    curl -sSL http://bit.ly/1tQvo5i | python - --username=admin --email=admin@example.com --password=admin
"""

import argparse
import os.path
import shutil
import subprocess
import sys

GIT_URL = 'https://github.com/mapbender/mapbender-starter.git'

# Command tuples (Command, Working dir)
INSTALL_CMDS = (
    ('git clone %s -b {branch} {dir}' % GIT_URL, None),
    ('git submodule update --init --recursive', '{dir}'),
    ('phing deps', '{dir}'),
    ('app/console doctrine:database:create', '{dir}/application'),
    ('app/console doctrine:schema:create', '{dir}/application'),
    ('app/console fom:user:reset --username={username} --email={email} '
        '--password={password} --silent', '{dir}/application'),
    ('app/console doctrine:fixtures:load '
        '--fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Epsg/'
        ' --append', '{dir}/application'),
)


def main(**kwargs):
    if os.path.exists(kwargs.get('dir')) and not kwargs.get('force_install'):
        update(**kwargs)
    else:
        install(**kwargs)


def install(**kwargs):
    if kwargs.get('force_install') and os.path.exists(kwargs.get('dir')):
        shutil.rmtree(kwargs.get('dir'))

    execute = True
    for cmd, cwd in INSTALL_CMDS:
        try:
            cmd = cmd.format(**kwargs)
            cwd = cwd.format(**kwargs) if cwd else None
            print("%s \"%s\" in \"%s\"" % (
                'Executing' if execute else 'Would execute',
                cmd,
                cwd if cwd else '.'))
            if execute:
                print('')
                subprocess.check_call(
                    cmd.split(' '),
                    cwd=cwd)
                print('')

        except subprocess.CalledProcessError:
            execute = False
    if not execute:
        sys.exit(1)


def update(**kwargs):
    pass


if __name__ == '__main__':
    parser = argparse.ArgumentParser(
        description=__doc__,
        formatter_class=argparse.RawTextHelpFormatter)
    parser.add_argument(
        'branch',
        metavar='BRANCH',
        type=str,
        default='develop',
        nargs='?',
        help='Branch to checkout')

    parser.add_argument(
        '-d', '--dir',
        metavar='DIRECTORY',
        type=str,
        help='Working directory, defaults to mapbender3_BRANCH')

    parser.add_argument(
        '--username',
        type=str,
        default='root',
        help='Username for admin account (root)')

    parser.add_argument(
        '--email',
        type=str,
        default='root@example.com',
        help='Email for admin account (root@example.com)')

    parser.add_argument(
        '--password',
        type=str,
        default='root',
        help='Password for admin account (root)')

    parser.add_argument(
        '--force-install',
        action='store_true',
        default=False,
        help='Delete and install again instead of update')

    args = parser.parse_args()

    args.dir = args.dir if args.dir else 'mapbender3_%s' % args.branch

    main(**vars(args))
