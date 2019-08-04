<?php
declare(strict_types=1);

namespace Xylis\MailboxLayer;

/**
 * Email class
 * @package Xylis\MailboxLayer
 * @link https://mailboxlayer.com/documentation#api_validation_tools
 */
class Email
{
    /** @var bool */
    private $smtpCheck;

    /** @var string */
    private $didYouMean;

    /** @var string */
    private $email;

    /** @var string */
    private $user;

    /** @var string */
    private $domain;

    /** @var bool */
    private $formatValid;

    /** @var bool */
    private $mxFound;

    /** @var bool|null */
    private $catchAll;

    /** @var bool */
    private $role;

    /** @var bool */
    private $disposable;

    /** @var bool */
    private $free;

    /** @var float */
    private $score;

    /**
     * @param bool $smtpCheck
     */
    public function setSmtpCheck(bool $smtpCheck)
    {
        $this->smtpCheck = $smtpCheck;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $domain
     */
    public function setDomain(?string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param bool $formatValid
     */
    public function setFormatValid(bool $formatValid)
    {
        $this->formatValid = $formatValid;
    }

    /**
     * @param bool $mxFound
     */
    public function setMxFound(?bool $mxFound)
    {
        $this->mxFound = $mxFound;
    }

    /**
     * @param bool|null $catchAll
     */
    public function setCatchAll(?bool $catchAll)
    {
        $this->catchAll = $catchAll;
    }

    /**
     * @param bool $role
     */
    public function setRole(bool $role)
    {
        $this->role = $role;
    }

    /**
     * @param bool $disposable
     */
    public function setDisposable(bool $disposable)
    {
        $this->disposable = $disposable;
    }

    /**
     * @param bool $free
     */
    public function setFree(bool $free)
    {
        $this->free = $free;
    }

    /**
     * @param float $score
     */
    public function setScore(float $score)
    {
        $this->score = $score;
    }

    /**
     * @param string $didYouMean
     */
    public function setDidYouMean($didYouMean)
    {
        $this->didYouMean = empty($this->didYouMean) ? null : $didYouMean;
    }

    /**
     * Contains the exact email address requested
     *
     * @return string
     */
    public function getEmail()
    {
        return (string) $this->email;
    }

    /**
     * Contains a did-you-mean suggestion in case a potential typo has been detected.
     *
     * @link https://mailboxlayer.com/documentation#typo_check
     * @return string|null
     */
    public function getSuggestion(): ?string
    {
        return (empty($this->didYouMean)) ? null : $this->didYouMean;
    }

    /**
     * Returns the local part of the request email address. (e.g. "paul" in "paul@company.com")
     *
     * @return string
     */
    public function getUser(): string
    {
        return (string) $this->user;
    }

    /**
     * Returns the domain of the requested email address. (e.g. "company.com" in "paul@company.com")
     *
     * @return string
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Returns a numeric score between 0 (bad) and 1 (good) reflecting the quality
     * and deliverability of the requested email address.
     *
     * @link https://mailboxlayer.com/documentation#quality_score
     * @return float
     */
    public function getQualityScore(): float
    {
        return (float) $this->score;
    }

    /**
     * Returns true or false depending on whether or not the general syntax of the requested email address is valid.
     *
     * @link https://mailboxlayer.com/documentation#syntax_check
     * @return bool
     */
    public function isFormatValid(): bool
    {
        return (bool) $this->formatValid;
    }

    /**
     * Returns true or false depending on whether or not MX-Records for the requested domain could be found.
     *
     * @link https://mailboxlayer.com/documentation#smtp_mx_check
     * @return bool
     */
    public function isMxFound(): bool
    {
        return ($this->mxFound === null) ? false : $this->mxFound;
    }

    /**
     * Returns true or false depending on whether or not the SMTP check of the requested email address succeeded.
     *
     * @link https://mailboxlayer.com/documentation#smtp_mx_check
     * @return bool
     */
    public function isSmtpValid(): bool
    {
        return (bool) $this->smtpCheck;
    }

    /**
     * Returns true or false depending on whether or not the
     * requested email address is a free email address. (e.g. "user123@gmail.com", "user123@yahoo.com")
     *
     * @link https://mailboxlayer.com/documentation#free_check
     * @return bool
     */
    public function isFreeDeliveredEmailAddress(): bool
    {
        return (bool) $this->free;
    }

    /**
     * Returns true or false depending on whether or not the requested email
     * address is a disposable email address. (e.g. "user123@mailinator.com")
     *
     * @link https://mailboxlayer.com/documentation#disposable_check
     * @return bool
     */
    public function isDisposable()
    {
        return (bool) $this->disposable;
    }

    /**
     * Returns true or false depending on whether or not the requested email address
     * is found to be part of a catch-all mailbox.
     *
     * @link https://mailboxlayer.com/documentation#catch_all
     * @return bool|null
     */
    public function isCatchAllMailbox(): ?bool
    {
        return $this->catchAll;
    }

    /**
     * Returns true or false depending on whether or not the requested email address
     * is a role email address. (e.g. "support@company.com", "postmaster@company.com")
     *
     * @link https://mailboxlayer.com/documentation#role_check
     * @return bool
     */
    public function isRoleEmailAddress(): bool
    {
        return (bool) $this->role;
    }

    /**
     * Return if email address provided is valid
     *
     * @see Email::isFormatValid()
     * @see Email::isMxFound()
     * @see Email::isSmtpValid()
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return ($this->isMxFound() && $this->isFormatValid() && $this->isSmtpValid());
    }
}
