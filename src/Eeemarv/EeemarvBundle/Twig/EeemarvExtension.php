<?php

namespace Eeemarv\EeemarvBundle\Twig;



class EeemarvExtension extends \Twig_Extension
{
    public function getFilters()
    {
		return array(
			new \Twig_SimpleFilter('strip_message', array($this, 'stripMessageFilter')),
			new \Twig_SimpleFilter('distance', array($this, 'distanceFilter')),
			new \Twig_SimpleFilter('tel', array($this, 'telFilter'))		
		);
    }
    
    public function getFunctions()
    {
		return array(
			new \Twig_SimpleFunction('getVersion', array($this, 'getVersion')),
		);
	}
	
	public function getVersion()
	{
		return exec('git describe --tags');
	}				
		
	public function stripMessageFilter($string)
	{
		return strip_tags($string, '<p><b><u><i><s><br><a><ul><ol><li><sup><sub><span><em><strike><strong><img>');
	}
	
	public function distanceFilter($number)
	{
		$number = round($number, 1);
		$number = ($number > 9.9) ? round($number) : $number;
		$number = ($number > 0.9) ? $number . ' km' : ($number * 1000) . ' m';
		return $number;
	}
	
	public function telFilter($tel)
	{
		$telnum = preg_replace('/\D/', '', $tel);
		if ($telnum){
			return '<a href="tel:'.$telnum.'">'.strip_tags($tel).'</a>';
		}	
		return null;	
	}	
		
		
	public function getName()
	{
		return 'eeemarv_twig_extension';
	} 
}
