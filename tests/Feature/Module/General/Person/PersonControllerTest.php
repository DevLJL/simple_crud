<?php

namespace Tests\Feature\Module\General\Person;

use App\Modules\General\Person\Entity\Person;
use App\Modules\General\Person\Repository\Eloquent\Model\PersonModelEloquent;
use App\Modules\General\Person\Repository\Eloquent\PersonRepositoryEloquent;
use App\Modules\General\Person\Repository\PersonRepositoryInterface;
use Exception;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    const PERSON_ENDPOINT = '/api/general/persons';
    private PersonRepositoryInterface $personRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->personRepository = new PersonRepositoryEloquent(new PersonModelEloquent());
    }

    /**
     * Gerar dados aleatório para Pessoa
     *
     * @return array
     */
    private function generateRandomPersonArray(): array
    {
        return [
            'id' => null,
            'name' => $this->faker->name,
            'cpf_cnpj' => onlyNumbers($this->faker->cpf),
            'email' => $this->faker->email,
            'date_of_birth' => $this->faker->date('Y-m-d'),
            'nationality' => $this->faker->randomElement(['Brasileira', 'Alemã', 'Estado-unidense', 'Francês']),
            'person_contacts' => [
                ['phone' => $this->faker->phoneNumber()],
                ['phone' => $this->faker->phoneNumber()],
            ],
            'created_at' => null,
            'updated_at' => null,
        ];
    }

    /**
     * Testar inclusão de Pessoa
     *
     * @return void
     */
    public function testStorePerson()
    {
        // Arrange
        $personArray = $this->generateRandomPersonArray();
        
        try {
            // Act   
            $response = $this->postJson(static::PERSON_ENDPOINT, $personArray);

            // Assert
            $expectedResponse = [
                'code',
                'error',
                'message',
                'data' => [
                    'id',
                    'name',
                    'cpf_cnpj',
                    'email',
                    'date_of_birth',
                    'nationality',
                    'created_at',
                    'updated_at',
                    'person_contacts' => [
                        '*' => [
                            'id',
                            'person_id',
                            'phone',
                        ]
                    ]
                ]
            ];
            $response
                ->assertStatus(201)
                ->assertJsonStructure($expectedResponse);
                
        } finally {
            // Clean
            $this->personRepository->destroy($response->json()['data']['id']);
        }
    }

    /**
     * Testar erro na inclusão de Documento inválido
     *
     * @return void
     */
    public function testStoreErrorWhenDocumentIsInvalid()
    {
        // Arrange
        $personArray = $this->generateRandomPersonArray();
        $personArray['cpf_cnpj'] = '12345678';
        
        // Act   
        $response = $this->postJson(static::PERSON_ENDPOINT, $personArray);

        // Assert
        $expectedResponse = [
            'code',
            'error',
            'message',
            'data' => [
                'cpf_cnpj'
            ]
        ];
        $response
            ->assertStatus(422)
            ->assertJsonStructure($expectedResponse);
    }

    /**
     * Testar atualização de Pessoa
     *
     * @return void
     */
    public function testUpdatePerson()
    {
        try {
            // Arrange... Incluir registro p/ posteriormente alterar
            $idStored = $this->personRepository->store(new Person(...$this->generateRandomPersonArray()));

            // Arrange... Gerar dados para atualizar Person
            $personArrayToUpdate = $this->generateRandomPersonArray();
            
            // Act
            $response = $this->putJson(static::PERSON_ENDPOINT.'/'.$idStored, $personArrayToUpdate);

            // Assert
            $expectedResponse = [
                'code',
                'error',
                'message',
                'data' => [
                    'id',
                    'name',
                    'cpf_cnpj',
                    'email',
                    'date_of_birth',
                    'nationality',
                    'created_at',
                    'updated_at',
                    'person_contacts' => [
                        '*' => [
                            'id',
                            'person_id',
                            'phone',
                        ]
                    ]
                ]
            ];
            $response
                ->assertStatus(200)
                ->assertJsonStructure($expectedResponse);
        } finally {
            // Clean
            $this->personRepository->destroy($idStored);
        }        
    }

    /**
     * Testar deleção de Pessoa
     *
     * @return void
     */
    public function testDestroyPerson()
    {
        try {
            // Arrange... Incluir registro p/ posteriormente deletar
            $idStored = $this->personRepository->store(new Person(...$this->generateRandomPersonArray()));

            // Act
            $response = $this->delete(static::PERSON_ENDPOINT.'/'.$idStored);

            // Assert
            $response
                ->assertStatus(204);
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * Testar método show de Pessoa
     *
     * @return void
     */
    public function testShowPerson()
    {
        try {
            // Arrange... Incluir registro p/ posteriormente obter com método Show
            $idStored = $this->personRepository->store(new Person(...$this->generateRandomPersonArray()));

            // Act
            $response = $this->getJson(static::PERSON_ENDPOINT.'/'.$idStored);

            // Assert
            $expectedResponse = [
                'code',
                'error',
                'message',
                'data' => [
                    'id',
                    'name',
                    'cpf_cnpj',
                    'email',
                    'date_of_birth',
                    'nationality',
                    'created_at',
                    'updated_at',
                    'person_contacts' => [
                        '*' => [
                            'id',
                            'person_id',
                            'phone',
                        ]
                    ]
                ]
            ];
            $response
                ->assertStatus(200)
                ->assertJsonStructure($expectedResponse);
        } finally {
            // Clean
            $this->personRepository->destroy($idStored);
        }        
    }

    /**
     * Testar método de listagem de Pessoas
     *
     * @return void
     */
    public function testIndexPerson()
    {
        try {
            // Arrange... Incluir registros para consultar posteriormente
            try {
                // Desativar controle de transação interna do repositório
                $this->personRepository->setTransaction(false);

                // Ativar transação manualmente
                $this->personRepository->startTransaction();

                // Inserir uma coleção de registros
                for ($i = 0; $i <= 10; $i++) {
                    $idsStored[] = $this->personRepository->store(new Person(...$this->generateRandomPersonArray()));
                }
    
                // Commitar transação manualmente
                $this->personRepository->commitTransaction();
            } catch (Exception $e) {
                // Rollback manualmente caso apresente algum erro
                $this->personRepository->rollBackTransaction();
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                return;
            }

            // Act
            $response = $this->getJson(static::PERSON_ENDPOINT);

            // Assert
            $expectedResponse = [
                'code',
                'error',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'cpf_cnpj',
                            'email',
                            'date_of_birth',
                            'nationality',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                    'from',
                    'last_page',
                    'per_page',
                    'to',
                    'total',
                ]
            ];
            $response
                ->assertStatus(200)
                ->assertJsonStructure($expectedResponse);
        } finally {
            // Clean
            foreach ($idsStored as $value) {
                $this->personRepository->destroy($value);
            }
        }        
    }

    /**
     * Testar paginação, limite, colunas informadas, ordenação e pesquisa customizada do método index
     *
     * @return void
     */
    public function testIndexFilterOptionsFromPerson()
    {
        try {
            // Arrange... Incluir registros para consultar posteriormente
            try {
                // Desativar controle de transação interna do repositório
                $this->personRepository->setTransaction(false);

                // Ativar transação manualmente
                $this->personRepository->startTransaction();

                // Inserir uma coleção de registros
                for ($i = 0; $i <= 10; $i++) {
                    $idsStored[] = $this->personRepository->store(new Person(...$this->generateRandomPersonArray()));
                }
                // Inserir registros específicos para testar pesquisa customizada
                $personCustom = new Person(...$this->generateRandomPersonArray());
                $personCustom->name = 'Leonam';
                $idsStored[] = $this->personRepository->store($personCustom);

                // Inserir registros específicos para testar pesquisa customizada +1
                $personCustom = new Person(...$this->generateRandomPersonArray());
                $personCustom->name = 'Leonardo';
                $idsStored[] = $this->personRepository->store($personCustom);

                // Inserir registros específicos para testar pesquisa customizada +1
                $personCustom = new Person(...$this->generateRandomPersonArray());
                $personCustom->name = 'Leoncio';
                $idsStored[] = $this->personRepository->store($personCustom);

                // Commitar transação manualmente
                $this->personRepository->commitTransaction();
            } catch (Exception $e) {
                // Rollback manualmente caso apresente algum erro
                $this->personRepository->rollBackTransaction();
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                return;
            }

            // Act
            $bodyJson = [
                'page' => 1,
                'limit' => 20,
                'columns' => "persons.id, persons.name",
                'order_by' => "persons.name",
                'custom_search' => [
                    "Leo"
                ]                
            ];
            $response = $this->getJson(static::PERSON_ENDPOINT, $bodyJson);

            // Assert
            $expectedResponse = [
                'code',
                'error',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                        ]
                    ],
                    'from',
                    'last_page',
                    'per_page',
                    'to',
                    'total',
                ]
            ];
            $response
                ->assertStatus(200)
                ->assertJsonStructure($expectedResponse);
            
            // Assert ...
            // O retorno deve ter no mínimo 3 registros devido as inserções de "Leonam, Leonardo e Leoncio".
            // No mínimo 3 ao invés de exato, porque também são inseridos registros aleatórios que podem conter Leo no nome
            $this->assertTrue(count($response->json()['data']['data']) >= 3);
        } finally {
            // Clean
            foreach ($idsStored as $value) {
                $this->personRepository->destroy($value);
            }
        }        
    }
}