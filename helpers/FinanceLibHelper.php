<?php
/**
 * FinanceLibHelper.php
 *
 * @author: matt tabin <amigo.tabin@gmail.com>
 * Date: 2/17/13
 * Time: 9:25 PM
 * 
 * Library of Helper UDFs for finance applications 
 * 
 */
class FinanceLibHelper
{
	/**
	 *  cumulative normal distribution function from Sanjay Ichalkaranje from http://php.net/manual/en/book.math.php
	 *  used for black-scholes pricing formula
	 */
	public static function cnd($val) {
    $p = floatval(0.2316419);
    $b1 = floatval(0.319381530);
    $b2 = floatval(-0.356563782);
    $b3 = floatval(1.781477937);
    $b4 = floatval(-1.821255978);
    $b5 = floatval(1.330274429);
    $t = 1/(1 + ($p * floatval($val)));
    $zx = (1/(sqrt(2 * pi())) * (exp(0 - pow($val, 2)/2)));

    $px = 1 - floatval($zx) * (($b1 * $t) + ($b2 * pow($t, 2)) + ($b3 * pow($t, 3)) + ($b4 * pow($t, 4)) + ($b5 * pow($t,5)));
    return $px;
	}

	/**
	 *	BlackScholesCalculator()
	 *	Computes the theoretical price of an equity option.
	 * 	allows you to figure out the value of a European call or put option.  The calculator uses the stock's current share price, 
	 *	the option strike price, time to expiration, risk-free interest rate, and volatility to derive the value of these options.  
	 *	The Black-Scholes calculation used here assumes no dividend is paid on the stock.
	 * 
	 * 	@param callPutFlag 					The Call Put Flag. (Required)."c" = Call else considered Put option.
	 * 	@param $currAssetPrice      The current asset price. (Required). 
	 * 	@param $exercisePrice      	Exercise price. (Required)
	 * 	@param $timeToMaturity      Time to maturity. (Required)
	 * 	@param $riskFreeInterestRate Risk-free Interest rate. (Required)
	 * 	@param $annualVolatility     Annualized volatility. (Required)
	 * 	@return Returns a number. 
	 */
	 
	public static function BlackScholesCalculator ($callPutFlag, $currAssetPrice, $exercisePrice, $timeToMaturity, $riskFreeInterestRate, $annualVolatility) {
    $d1 = ( log($currAssetPrice / $exercisePrice) + ($riskFreeInterestRate + (pow($annualVolatility,2)) / 2) * $timeToMaturity) / ($annualVolatility * (pow($timeToMaturity,0.5)));
    $d2 = $d1 - $annualVolatility * (pow($timeToMaturity,0.5));

    if ($callPutFlag === 'c')
        return $currAssetPrice * self::cnd($d1) - $exercisePrice * exp(-$riskFreeInterestRate * $timeToMaturity) * self::cnd($d2);
    else
        return $exercisePrice * exp(-$riskFreeInterestRate * $timeToMaturity) * self::cnd(-$d2) - $currAssetPrice * self::cnd(-$d1);
	}
}