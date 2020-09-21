<?php


namespace ElegantMedia\PHPToolkit;

use ElegantMedia\PHPToolkit\Exceptions\FileSystem\FileNotFoundException;
use ElegantMedia\PHPToolkit\Exceptions\FileSystem\SectionAlreadyExistsException;

class FileEditor
{


	/**
	 *
	 * Check if a section exists, and if not, append contents
	 *
	 * @param      $filePath
	 * @param      $stubPath
	 * @param      $sectionStartString
	 * @param null $sectionEndString
	 * @param bool $throwEx				Returns the number of bytes written or FALSE on failure
	 *
	 * @return bool|int
	 * @throws SectionAlreadyExistsException
	 * @throws FileNotFoundException
	 */
	public static function appendStubIfSectionNotFound(
		$filePath,
		$stubPath,
		$sectionStartString = null,
		$sectionEndString = null,
		$throwEx = false
	) {
		if (!file_exists($filePath)) {
			throw new FileNotFoundException("File {$filePath} not found");
		}
		if (!file_exists($stubPath)) {
			throw new FileNotFoundException("File {$stubPath} not found");
		}

		// by default, we use the first line of the stub as the section start line
		if (empty($sectionStartString)) {
			$sectionStartString = self::readFirstLine($stubPath);
		}

		if (empty($sectionStartString)) {
			throw new \InvalidArgumentException("A section start string is required.");
		}

		// check if the routes file mentions anything about the $sectionStartString
		// if so, it might already be there. Ask the user to confirm.
		if (self::isTextInFile($filePath, $sectionStartString, false)) {
			if ($throwEx) {
				throw new SectionAlreadyExistsException();
			}
		}

		return self::appendStub($filePath, $stubPath, false);
	}

	/**
	 *
	 * Append a stub to an existing file
	 *
	 * @param $filePath
	 * @param $stubPath
	 *
	 * @param bool $verifyPathsExists
	 * @param bool $stripOpenTag
	 * @return bool|int
	 * @throws FileNotFoundException
	 */
	public static function appendStub($filePath, $stubPath, $verifyPathsExists = true, $stripOpenTag = true)
	{
		if ($verifyPathsExists) {
			if (!file_exists($filePath)) {
				throw new FileNotFoundException("File {$filePath} not found");
			}
			if (!file_exists($stubPath)) {
				throw new FileNotFoundException("File {$stubPath} not found");
			}
		}

		// get contents and update the file
		$contents = file_get_contents($stubPath);

		// strip open PHP tags
		if ($stripOpenTag) {
			$tagRegex = '/^\s?<\?(?:php|=)/';
			$contents = preg_replace($tagRegex, '', $contents);
		}

		// add a new line
		$contents = "\r\n" . trim($contents);

		return file_put_contents($filePath, $contents, FILE_APPEND);
	}


	/**
	 *
	 * Check if a string exists in a file. (Don't use to check on large files)
	 *
	 * @param      $filePath
	 * @param      $string
	 * @param bool $caseSensitive
	 *
	 * @return bool
	 * @throws FileNotFoundException
	 */
	public static function isTextInFile($filePath, $string, $caseSensitive = true): bool
	{
		if (!file_exists($filePath)) {
			throw new FileNotFoundException("File $filePath not found");
		}

		$command = ($caseSensitive)? 'strpos': 'stripos';

		return $command(file_get_contents($filePath), $string) !== false;
	}


	/**
	 *
	 * Check if two files are identical in content
	 *
	 * @param $path1
	 * @param $path2
	 *
	 * @return bool
	 * @throws FileNotFoundException
	 */
	public static function areFilesSimilar($path1, $path2): bool
	{
		if (!file_exists($path1) || !file_exists($path2)) {
			throw new FileNotFoundException("At least one of the requested files not found. {$path1}, {$path2}");
		}

		return ((filesize($path1) == filesize($path2)) && (md5_file($path1) == md5_file($path2)));
	}

	/**
	 *
	 * Returns the first line from an existing file.
	 *
	 * @param $filePath
	 *
	 * @param bool $trim
	 * @param bool $skipOpenTag
	 * @return bool|string
	 */
	public static function readFirstLine($filePath, $trim = true, $skipOpenTag = true)
	{
		$handle = fopen($filePath, 'rb');

		$startingLine = null;
		$startingTagFound = false;

		while (!feof($handle)) {
			$line = fgets($handle);

			// trim the line
			if ($trim) {
				$line = trim($line);
			}

			if (!$startingTagFound) {
				// strip starting PHP tags
				if ($skipOpenTag) {
					$tagRegex = '/^\s?<\?(?:php|=)/';
					if (preg_match($tagRegex, $line)) {
						$startingTagFound = true;
						$line = preg_replace($tagRegex, '', $line);
					}
				}
			}

			if (strlen($line) > 0) {
				$startingLine = $line;
				break;
			}
		}

		fclose($handle);

//		if ($trim) {
//			return trim($line);
//		}

		return $startingLine;
	}


	/**
	 *
	 * Get a Classname from a file. Only reads the file without parsing for syntax.
	 * @link https://stackoverflow.com/questions/7153000/get-class-name-from-file
	 *
	 * @param $filePath
	 * @param bool $withNamespace
	 * @param bool $stripLeading
	 * @return string
	 * @throws FileNotFoundException
	 */
	public static function getPHPClassName($filePath, $withNamespace = true, $stripLeading = false): string
	{
		if (!file_exists($filePath)) {
			throw new FileNotFoundException("Filing {$filePath} not found.");
		}

		$fp = fopen($filePath, 'r');
		$class = $namespace = $buffer = '';
		$i = 0;
		while (!$class) {
			if (feof($fp)) {
				break;
			}

			$buffer .= fread($fp, 512);
			$tokens = token_get_all($buffer);

			if (strpos($buffer, '{') === false) {
				continue;
			}

			for ($iMax = count($tokens); $i< $iMax; $i++) {
				if ($tokens[$i][0] === T_NAMESPACE) {
					for ($j=$i+1, $jMax = count($tokens); $j< $jMax; $j++) {
						if ($tokens[$j][0] === T_STRING) {
							$namespace .= '\\'.$tokens[$j][1];
						} elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
							break;
						}
					}
				}

				if ($tokens[$i][0] === T_CLASS) {
					for ($j=$i+1, $jMax = count($tokens); $j< $jMax; $j++) {
						if ($tokens[$j] === '{') {
							$class = $tokens[$i+2][1];
						}
					}
				}
			}
		}

		$response = [];

		if ($withNamespace) {
			$response[] = $namespace;
		}

		$response[] = $class;

		$response = implode('\\', $response);

		if ($stripLeading) {
			$response = ltrim($response, '\\');
		}

		return $response;
	}
}
