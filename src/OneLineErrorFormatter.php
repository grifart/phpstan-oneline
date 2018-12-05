<?php declare(strict_types = 1);

namespace Grifart\PhpstanOneLine;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\File\RelativePathHelper;
use Symfony\Component\Console\Style\OutputStyle;

class OneLineErrorFormatter implements ErrorFormatter
{
	/** @var RelativePathHelper */
	private $relativePathHelper;

	public function __construct(
		RelativePathHelper $relativePathHelper
	)
	{
		$this->relativePathHelper = $relativePathHelper;
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
				sprintf(
					'%s:%d %s',
					$this->relativePathHelper->getRelativePath($fileSpecificError->getFile()),
					$fileSpecificError->getLine() ?? '?',
					$fileSpecificError->getMessage()
				)
			);
		}

		$style->error(sprintf($analysisResult->getTotalErrorsCount() === 1 ? 'Found %d error' : 'Found %d errors', $analysisResult->getTotalErrorsCount()));
		return 1;
	}

}
