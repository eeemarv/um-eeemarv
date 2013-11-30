<?php

namespace Eeemarv\EeemarvBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * GeoPythagorasDistanceFunction ::= GEO_PYTHA_DISTANCE(latOrigin, lngOrigin, latDestination, lngDestination) => distance in km
 */
class GeoPythagorasDistanceFunction extends FunctionNode {

	protected $latitude1;
	protected $longitude1;
	protected $latitude2;
	protected $longitude2;

	public function parse(Parser $parser) {
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->latitude1 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->longitude1 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->latitude2 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->longitude2 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}

	public function getSql(SqlWalker $sqlWalker) {
		return sprintf(
//			'12756 * ASIN(SQRT(POWER(SIN((%s - %s) * PI()/360), 2) + COS(%s * PI()/180) * COS(%s * PI()/180) * POWER(SIN((%s - %s) *  PI()/360), 2)))',
			'6378 * SQRT(POWER((%s - %s), 2) + POWER((COS(%s - %s) * PI()/180 * (%s - %s)), 2))',
						
			$sqlWalker->walkArithmeticPrimary($this->latitude1),
			$sqlWalker->walkArithmeticPrimary($this->latitude2),
			$sqlWalker->walkArithmeticPrimary($this->latitude1),
			$sqlWalker->walkArithmeticPrimary($this->latitude2),
			$sqlWalker->walkArithmeticPrimary($this->longitude1),
			$sqlWalker->walkArithmeticPrimary($this->longitude2)
		);
	}

}

