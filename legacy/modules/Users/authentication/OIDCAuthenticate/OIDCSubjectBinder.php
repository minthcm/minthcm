<?php
/**
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2024 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Class OIDCSubjectBinder
 *
 * Binds a MintHCM user to the immutable OIDC `sub` claim (trust on first use) so that
 * a mutable claim used for the username (preferred_username, email) cannot be reused
 * to sign in to another person's account ("nOAuth").
 *
 * The first successful OIDC login stores the subject; every later login must present
 * the same subject. This class is the only writer of users.oidc_subject — an
 * administrator resets the binding from the user record or with MintCLI.
 */
class OIDCSubjectBinder
{
    /** @var DBManager */
    private $db;

    public function __construct(?DBManager $db = null)
    {
        $this->db = $db ?? DBManagerFactory::getInstance();
    }

    /**
     * @param string $user_id ID of the user resolved from the username claim.
     * @param string $subject `sub` claim of the validated ID token.
     * @return bool True when the user may log in with this subject.
     */
    public function bind(string $user_id, string $subject): bool
    {
        if ($user_id === '' || $subject === '') {
            return false;
        }

        $stored_subject = $this->getSubject($user_id);
        if ($stored_subject !== '') {
            return hash_equals($stored_subject, $subject);
        }

        if ($this->isBoundToOtherUser($user_id, $subject)) {
            $GLOBALS['log']->fatal('SECURITY: OIDCSubjectBinder: subject is already bound to another user, refusing to bind user ' . $user_id);
            return false;
        }

        // Conditional update: of two concurrent first logins only the one stored first wins.
        $this->db->query(
            'UPDATE users SET oidc_subject = ' . $this->db->quoted($subject)
            . ' WHERE id = ' . $this->db->quoted($user_id)
            . " AND (oidc_subject IS NULL OR oidc_subject = '')"
        );
        if (!hash_equals($this->getSubject($user_id), $subject)) {
            return false;
        }

        // Concurrent first logins of two different accounts with the same subject both pass the
        // check above. Re-check after the write and withdraw our binding on collision: at worst
        // both logins are refused (and the next attempt binds one), never both accounts bound.
        if ($this->isBoundToOtherUser($user_id, $subject)) {
            $this->db->query(
                'UPDATE users SET oidc_subject = NULL WHERE id = ' . $this->db->quoted($user_id)
                . ' AND oidc_subject = ' . $this->db->quoted($subject)
            );
            $GLOBALS['log']->fatal('SECURITY: OIDCSubjectBinder: subject was concurrently bound to another user, refusing to bind user ' . $user_id);
            return false;
        }
        $GLOBALS['log']->info('OIDCSubjectBinder: bound OIDC subject to user ' . $user_id);

        return true;
    }

    /**
     * Removes the binding of one user; the next OIDC login binds the account again.
     *
     * @return bool True when a binding existed and was removed.
     */
    public function unbind(string $user_id): bool
    {
        if ($this->getSubject($user_id) === '') {
            return false;
        }
        $this->db->query('UPDATE users SET oidc_subject = NULL WHERE id = ' . $this->db->quoted($user_id));
        return true;
    }

    /**
     * Removes the bindings of all users, e.g. after switching to another IdP.
     *
     * @return int Number of removed bindings.
     */
    public function unbindAll(): int
    {
        $count = (int) $this->db->getOne("SELECT COUNT(*) FROM users WHERE oidc_subject IS NOT NULL AND oidc_subject <> ''");
        $this->db->query("UPDATE users SET oidc_subject = NULL WHERE oidc_subject IS NOT NULL AND oidc_subject <> ''");
        return $count;
    }

    public function getSubject(string $user_id): string
    {
        // Read without HTML encoding (DBManager::getOne() encodes), so a subject containing
        // characters such as ' or & is compared exactly as the IdP sent it.
        $result = $this->db->query('SELECT oidc_subject FROM users WHERE id = ' . $this->db->quoted($user_id));
        $row = $this->db->fetchByAssoc($result, false);
        return (string) ($row['oidc_subject'] ?? '');
    }

    private function isBoundToOtherUser(string $user_id, string $subject): bool
    {
        $other_user_id = $this->db->getOne(
            'SELECT id FROM users WHERE oidc_subject = ' . $this->db->quoted($subject)
            . ' AND id <> ' . $this->db->quoted($user_id)
            . ' AND deleted = 0'
        );
        return !empty($other_user_id);
    }
}
