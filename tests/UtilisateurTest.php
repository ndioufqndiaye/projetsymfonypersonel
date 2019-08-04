<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class UtilisateurTest extends WebTestCase
{
   /** @test */
    public function TestUtilisateur()
    {
        $client = static::createClient([],[

            'PHP_AUTH_USER'=>'Admin',
            'PHP_AUTH_PW'=>'pass1234'
        ]

        );
 
        $crawler = $client->request('POST', '/api/partenaire',[],[],

        ['CONTENT_TYPE'=>"application/json"],'
        {
            "username":"ndiouga",
            "roles":["ROLE_ADMIN"],
            "password":"ndiaye",
            "matricule":"AB1233",
            "nom":"fal",
            "prenom":"doud",
            "nomPartenaire":"ndiay & frere",
            "ninea":"1203AAA1110",
            "email":"ndiay@gmail.com",
            "adresse":"thias",
            "telephone":784521256,
            "status":"activer",
            "solde":0
        
               
           }');

           $rep=$client->getResponse();
           var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }
}
