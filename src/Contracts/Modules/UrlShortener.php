<?php 

namespace App\Contracts\Modules;

interface UrlShortener {
	public function IsValid(string $url): bool;
	public function Shorten(string $url): string;
	public function Expand(string $key): string;
	public function Update(string $key, string $url);
	public function Remove(string $key);
}

?>
