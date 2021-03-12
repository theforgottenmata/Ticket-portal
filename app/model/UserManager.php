<?php


namespace App\Model;

use Exception;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

/**
 * Model pro správu uživatelů v systému.
 * @package App\Model
 */
class UserManager extends DatabaseManager implements IAuthenticator
{
	const
		TABLE_NAME = 'user',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role';


	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials; // Extrahuje potřebné přihlašovací údaje.

		// Najde a vrátí první záznam uživatele s daným jménem v databázi nebo false, pokud takový uživatel neexistuje.
		$user = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();

		// Ověření uživatele.
		if (!$user) { // Vyhodí výjimku, pokud uživatel neexituje.
			throw new AuthenticationException('Zadané uživatelské jméno neexistuje.', self::IDENTITY_NOT_FOUND);
		} else if (!Passwords::verify($password, $user[self::COLUMN_PASSWORD_HASH])) { // Ověří zadané heslo.
			// Vyhodí výjimku, pokud je heslo špatně.
			throw new AuthenticationException('Zadané heslo není správně.', self::INVALID_CREDENTIAL);
		} else if (Passwords::needsRehash($user[self::COLUMN_PASSWORD_HASH])) { // Zjistí zda heslo potřebuje rehashovat.
			// Rehashuje heslo (bezpečnostní opatření).
			$user->update([self::COLUMN_PASSWORD_HASH => Passwords::hash($password)]);
		}

		// Příprava atributů z databáze pro identitu úspěšně přihlášeného uživatele.
		$userAttributes = $user->toArray(); // Převede uživatelská data z databáze na PHP pole.
		unset($userAttributes[self::COLUMN_PASSWORD_HASH]); // Odstraní hash hesla z uživatelských dat (kvůli bezpečnosti).

		// Vrátí novou identitu úspěšně přihlášeného uživatele.
		return new Identity($user[self::COLUMN_ID], $user[self::COLUMN_ROLE], $userAttributes);
	}

	public function register($username, $password)
	{
		try {
			// Pokusí se vložit nového uživatele do databáze.
			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_NAME => $username,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password)
			]);
		} catch (UniqueConstraintViolationException $e) {
			// Vyhodí výjimku, pokud uživatel s daným jménem již existuje.
			throw new DuplicateNameException;
		}
	}
}


class DuplicateNameException extends Exception
{
	// Nastavení výchozí chybové zprávy.
	protected $message = 'Uživatel s tímto jménem je již zaregistrovaný.';
}
