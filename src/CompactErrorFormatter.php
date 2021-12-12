<?php declare(strict_types = 1);

namespace Grifart\PhpstanOneLine;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\Command\Output;
use PHPStan\File\RelativePathHelper;

class CompactErrorFormatter implements ErrorFormatter
{
	/** @var RelativePathHelper */
	private $relativePathHelper;

	/** @var string */
	private $format;

	/** @var string */
	private $editorUrl;

	public function __construct(
		RelativePathHelper $relativePathHelper,
		string $format,
		?string $editorUrl
	)
	{
		$this->relativePathHelper = $relativePathHelper;
		$this->format = $format;
		$this->editorUrl = $editorUrl ?? '';
	}

	public function formatErrors(
		AnalysisResult $analysisResult,
		Output $output
	): int
	{
		if (!$analysisResult->hasErrors()) {
			$output->writeLineFormatted('No errors');
			return 0;
		}

		foreach ($analysisResult->getNotFileSpecificErrors() as $notFileSpecificError) {
			$output->writeLineFormatted(sprintf('<unknown location> %s', $notFileSpecificError));
		}

		foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
			$absolutePath = method_exists($fileSpecificError, 'getTraitFilePath')
				? $fileSpecificError->getTraitFilePath() ?? $fileSpecificError->getFilePath()
				: $fileSpecificError->getFile();

			$output->writeLineFormatted(
				strtr(
					$this->format,
					[
						'{absolutePath}' => $absolutePath,
                        '{editorUrl}' => str_replace(['%file%', '%line%'], [$absolutePath, $fileSpecificError->getLine() ?? ''], $this->editorUrl),
                        '{path}' => $this->relativePathHelper->getRelativePath($fileSpecificError->getFile()),
						'{line}' => $fileSpecificError->getLine() ?? '?',
						'{error}' => $fileSpecificError->getMessage(),
					]
				)
			);
		}

		$output->writeRaw(sprintf(
			'Found %d error%s',
			$analysisResult->getTotalErrorsCount(),
			$analysisResult->getTotalErrorsCount() === 1 ? '' : 's'
		));
		$output->writeLineFormatted('');
		return 1;
	}
}
