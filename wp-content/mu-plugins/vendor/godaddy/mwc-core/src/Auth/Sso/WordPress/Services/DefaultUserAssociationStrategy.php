<?php

namespace GoDaddy\WordPress\MWC\Core\Auth\Sso\WordPress\Services;

use GoDaddy\WordPress\MWC\Common\DataSources\WordPress\Adapters\UserAdapter;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\SanitizationHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\TypeHelper;
use GoDaddy\WordPress\MWC\Common\Models\User;
use GoDaddy\WordPress\MWC\Core\Auth\Sso\WordPress\Exceptions\UserHasNoValidIdException;
use GoDaddy\WordPress\MWC\Core\Auth\Sso\WordPress\Exceptions\UserNotFoundException;
use WP_User;
use WP_User_Query;

/**
 * Default user association strategy, which finds an account based on a customer ID or username match.
 */
class DefaultUserAssociationStrategy extends AbstractUserAssociationStrategy
{
    public const SSO_CUSTOMER_ID_META_KEY = '_gd_sso_customer_id';

    /**
     * Gets the local user associated with the given token.
     * We prefer to search by GoDaddy customer ID, but if that fails we fall back to matching based on username.
     *
     * @return User
     * @throws UserNotFoundException|UserHasNoValidIdException
     */
    public function getLocalUser() : User
    {
        $customerId = $this->token->getCustomerId();

        if ($user = $this->getUserByCustomerId($customerId)) {
            return $user;
        } elseif ($user = $this->getUserByHandle($this->token->getUsername())) {
            // Save this association, so we can use customer ID next time we authenticate.
            $this->associateUserWithCustomerId($user, $customerId);

            return $user;
        } else {
            throw new UserNotFoundException('No local user found for the given token.');
        }
    }

    /**
     * Gets the local {@see User}  associated with the provided GoDaddy customer ID.
     *
     * @param string $customerId
     * @return ?User
     */
    protected function getUserByCustomerId(string $customerId) : ?User
    {
        $users = $this->getWpUser($customerId);

        $user = ArrayHelper::get($users, '0');

        if ($user instanceof WP_User) {
            return User::seed((UserAdapter::getNewInstance($user))->convertFromSource());
        }

        return null;
    }

    /**
     * Get the WP_User that has metadata matching the GD customer ID.
     *
     * @param string $customerId
     * @return array<mixed>
     */
    protected function getWpUser(string $customerId) : array
    {
        return (new WP_User_Query([
            'meta_key'    => static::SSO_CUSTOMER_ID_META_KEY,
            'meta_value'  => $customerId,
            'count_total' => false,
            'number'      => 1,
        ]))->get_results();
    }

    /**
     * Gets the {@see User} by WordPress username.
     *
     * @param string $username
     * @return User|null
     */
    protected function getUserByHandle(string $username) : ?User
    {
        return User::getByHandle(SanitizationHelper::username($username));
    }

    /**
     * Associates the {@see User} with the GoDaddy customer ID.
     *
     * @param User $user with ID
     * @param string $customerId
     * @return void
     * @throws UserHasNoValidIdException
     */
    protected function associateUserWithCustomerId(User $user, string $customerId) : void
    {
        $userId = TypeHelper::int($user->getId(), 0);

        if (! $userId) {
            throw new UserHasNoValidIdException('Cannot associate user with customer ID: user has no valid ID.');
        }

        update_user_meta($userId, static::SSO_CUSTOMER_ID_META_KEY, $customerId);
    }
}
