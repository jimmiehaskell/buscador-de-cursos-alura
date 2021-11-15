<?php

namespace HaskellDev\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    private $httpClient;
    private $crawler;
    private $contador = 0;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->crawler = $crawler;
        $this->httpClient = $httpClient;
    }

    public function buscar(string $url): array
    {
        $resposta = $this->httpClient->request('GET', $url);

        $html = $resposta->getBody();
        $this->crawler->addHtmlContent($html);

        $elementosCursos = $this->crawler->filter(selector: 'span.card-curso__nome');

        $cursos = [];

        foreach ($elementosCursos as $elemento) {
            if ($this->contador === 0) {
                $this->contador = 1;
            }
            $cursos[] = "$this->contador - $elemento->textContent";
            $this->contador++;
        }

        return $cursos;
    }
}
