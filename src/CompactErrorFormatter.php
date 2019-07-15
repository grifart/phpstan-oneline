<?php declare(strict_types = 1);

namespace Grifart\PhpstanOneLine;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\File\RelativePathHelper;
use Symfony\Component\Console\Style\OutputStyle;

class CompactErrorFormatter implements ErrorFormatter
{
	/** @var RelativePathHelper */
	private $relativePathHelper;

	/** @var string */
	private $format;

	public function __construct(
		RelativePathHelper $relativePathHelper,
		string $format
	)
	{
		$this->relativePathHelper = $relativePathHelper;
		$this->format = $format;
	}

	public function formatErrors(
		AnalysisResult $analysisResult,
		OutputStyle $style
	): int
	{
		if (!$analysisResult->hasErrors()) {
			$style->success('No errors');
			return 0;
		}

		foreach ($analysisResult->getNotFileSpecificErrors() as $notFileSpecificError) {
			$style->writeln(sprintf('<unknown location> %s', $notFileSpecificError));
		}

		foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
			$style->writeln(
				strtr(
					$this->format,
					[
						'{absolutePath}' => $fileSpecificError->getFile(),
						'{path}' => $this->relativePathHelper->getRelativePath($fileSpecificError->getFile()),
						'{line}' => $fileSpecificError->getLine() ?? '?',
						'{error}' => $fileSpecificError->getMessage(),
					]
				)
			);
		}

		$style->error(sprintf($analysisResult->getTotalErrorsCount() === 1 ? 'Found %d error' : 'Found %d errors', $analysisResult->getTotalErrorsCount()));
		return 1;
	}

}
