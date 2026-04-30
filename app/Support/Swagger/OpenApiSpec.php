<?php

namespace App\Support\Swagger;

class OpenApiSpec
{
    public static function document(): array
    {
        return [
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'Instaclone API',
                'version' => '1.0.0',
                'description' => 'Documentação da API do Instaclone com Swagger UI.',
            ],
            'servers' => [
                [
                    'url' => url('/api'),
                    'description' => 'API local',
                ],
            ],
            'tags' => [
                ['name' => 'Auth'],
                ['name' => 'Users'],
                ['name' => 'Posts'],
                ['name' => 'Feed'],
                ['name' => 'Likes'],
                ['name' => 'Comments'],
                ['name' => 'Follows'],
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'Sanctum token',
                    ],
                ],
                'schemas' => [
                    'User' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'name' => ['type' => 'string', 'example' => 'Ana Souza'],
                            'username' => ['type' => 'string', 'example' => 'anasouza'],
                            'bio' => ['type' => ['string', 'null'], 'example' => 'Fotógrafa e criadora de conteúdo'],
                            'avatar_url' => ['type' => ['string', 'null'], 'example' => 'http://localhost/storage/avatars/ana.jpg'],
                        ],
                    ],
                    'Post' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 10],
                            'caption' => ['type' => ['string', 'null'], 'example' => 'Novo post no feed'],
                            'image_url' => ['type' => 'string', 'example' => 'http://localhost/storage/posts/post.jpg'],
                            'user' => ['$ref' => '#/components/schemas/User'],
                            'likes_count' => ['type' => 'integer', 'example' => 12],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'Comment' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 25],
                            'body' => ['type' => 'string', 'example' => 'Ótimo clique!'],
                            'user' => ['$ref' => '#/components/schemas/User'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'AuthResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'user' => ['$ref' => '#/components/schemas/User'],
                            'token' => ['type' => 'string', 'example' => '1|plain-text-sanctum-token'],
                        ],
                    ],
                    'RefreshResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'token' => ['type' => 'string', 'example' => '1|new-plain-text-sanctum-token'],
                        ],
                    ],
                    'MessageResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => ['type' => 'string'],
                        ],
                    ],
                    'FollowStatus' => [
                        'type' => 'object',
                        'properties' => [
                            'following' => ['type' => 'boolean', 'example' => true],
                        ],
                    ],
                    'UnreadCountResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'unread_count' => ['type' => 'integer', 'example' => 4],
                        ],
                    ],
                    'LikeResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'liked' => ['type' => 'boolean', 'example' => true],
                            'likes_count' => ['type' => 'integer', 'example' => 42],
                        ],
                    ],
                ],
            ],
            'paths' => [
                '/auth/register' => [
                    'post' => [
                        'tags' => ['Auth'],
                        'summary' => 'Cria uma conta e retorna token',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['name', 'email', 'password', 'password_confirmation'],
                                        'properties' => [
                                            'name' => ['type' => 'string', 'example' => 'Ana Souza'],
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'ana@example.com'],
                                            'password' => ['type' => 'string', 'format' => 'password', 'example' => 'secret123'],
                                            'password_confirmation' => ['type' => 'string', 'format' => 'password', 'example' => 'secret123'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Usuário criado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/AuthResponse']]],
                            ],
                            '422' => ['description' => 'Validação inválida'],
                        ],
                    ],
                ],
                '/auth/login' => [
                    'post' => [
                        'tags' => ['Auth'],
                        'summary' => 'Autentica um usuário',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'ana@example.com'],
                                            'password' => ['type' => 'string', 'format' => 'password', 'example' => 'secret123'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Login realizado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/AuthResponse']]],
                            ],
                            '422' => ['description' => 'Credenciais inválidas'],
                        ],
                    ],
                ],
                '/auth/logout' => [
                    'post' => [
                        'tags' => ['Auth'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Revoga o token atual',
                        'responses' => [
                            '200' => [
                                'description' => 'Logout realizado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/MessageResponse']]],
                            ],
                        ],
                    ],
                ],
                '/auth/refresh' => [
                    'post' => [
                        'tags' => ['Auth'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Gera um novo token',
                        'responses' => [
                            '200' => [
                                'description' => 'Token renovado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/RefreshResponse']]],
                            ],
                        ],
                    ],
                ],
                '/auth/me' => [
                    'get' => [
                        'tags' => ['Auth'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Retorna o usuário autenticado',
                        'responses' => [
                            '200' => [
                                'description' => 'Usuário autenticado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/User']]],
                            ],
                        ],
                    ],
                ],
                '/users/search' => [
                    'get' => [
                        'tags' => ['Users'],
                        'summary' => 'Pesquisa usuários por texto',
                        'parameters' => [[
                            'name' => 'q',
                            'in' => 'query',
                            'required' => true,
                            'schema' => ['type' => 'string'],
                            'example' => 'ana',
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de usuários',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/User'],
                                ]]],
                            ],
                        ],
                    ],
                ],
                '/users/suggestions' => [
                    'get' => [
                        'tags' => ['Users'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Sugestões para o usuário autenticado',
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de sugestões',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/User'],
                                ]]],
                            ],
                        ],
                    ],
                ],
                '/users/me' => [
                    'put' => [
                        'tags' => ['Users'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Atualiza o perfil autenticado',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'name' => ['type' => 'string'],
                                            'username' => ['type' => 'string'],
                                            'bio' => ['type' => 'string'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Perfil atualizado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/User']]],
                            ],
                        ],
                    ],
                ],
                '/users/me/avatar' => [
                    'post' => [
                        'tags' => ['Users'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Envia avatar do perfil',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'multipart/form-data' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['avatar'],
                                        'properties' => [
                                            'avatar' => ['type' => 'string', 'format' => 'binary'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Avatar atualizado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/User']]],
                            ],
                        ],
                    ],
                ],
                '/users/{username}' => [
                    'get' => [
                        'tags' => ['Users'],
                        'summary' => 'Exibe perfil por username ou id numérico',
                        'parameters' => [[
                            'name' => 'username',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'string'],
                            'example' => 'anasouza',
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Perfil encontrado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/User']]],
                            ],
                        ],
                    ],
                ],
                '/users/{id}/follow' => [
                    'post' => [
                        'tags' => ['Follows'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Segue um usuário',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Usuário seguido',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/MessageResponse']]],
                            ],
                        ],
                    ],
                    'delete' => [
                        'tags' => ['Follows'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Deixa de seguir um usuário',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Usuário removido da lista de seguindo',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/MessageResponse']]],
                            ],
                        ],
                    ],
                ],
                '/users/{id}/is-following' => [
                    'get' => [
                        'tags' => ['Follows'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Verifica se está seguindo',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Status de follow',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/FollowStatus']]],
                            ],
                        ],
                    ],
                ],
                '/users/{id}/followers' => [
                    'get' => [
                        'tags' => ['Follows'],
                        'summary' => 'Lista seguidores de um usuário',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de usuários',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/User'],
                                ]]],
                            ],
                        ],
                    ],
                ],
                '/users/{id}/following' => [
                    'get' => [
                        'tags' => ['Follows'],
                        'summary' => 'Lista quem um usuário segue',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de usuários',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/User'],
                                ]]],
                            ],
                        ],
                    ],
                ],
                '/posts' => [
                    'post' => [
                        'tags' => ['Posts'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Cria um post',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'multipart/form-data' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['image'],
                                        'properties' => [
                                            'image' => ['type' => 'string', 'format' => 'binary'],
                                            'caption' => ['type' => 'string'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Post criado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Post']]],
                            ],
                        ],
                    ],
                ],
                '/posts/{id}' => [
                    'get' => [
                        'tags' => ['Posts'],
                        'summary' => 'Mostra um post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Post encontrado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Post']]],
                            ],
                        ],
                    ],
                    'put' => [
                        'tags' => ['Posts'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Atualiza a legenda do post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['caption'],
                                        'properties' => [
                                            'caption' => ['type' => 'string', 'example' => 'Legenda atualizada'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Post atualizado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Post']]],
                            ],
                        ],
                    ],
                    'delete' => [
                        'tags' => ['Posts'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Remove um post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Post removido',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/MessageResponse']]],
                            ],
                        ],
                    ],
                ],
                '/posts/{id}/likes' => [
                    'get' => [
                        'tags' => ['Likes'],
                        'summary' => 'Lista usuários que curtiram um post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de usuários',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/User'],
                                ]]],
                            ],
                        ],
                    ],
                ],
                '/posts/{id}/like' => [
                    'post' => [
                        'tags' => ['Likes'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Curte um post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Post curtido',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/LikeResponse']]],
                            ],
                        ],
                    ],
                    'delete' => [
                        'tags' => ['Likes'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Descurte um post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Post descurtido',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/LikeResponse']]],
                            ],
                        ],
                    ],
                ],
                '/posts/{id}/comments' => [
                    'get' => [
                        'tags' => ['Comments'],
                        'summary' => 'Lista comentários de um post',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Comentários paginados',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'data' => [
                                            'type' => 'array',
                                            'items' => ['$ref' => '#/components/schemas/Comment'],
                                        ],
                                        'current_page' => ['type' => 'integer'],
                                        'last_page' => ['type' => 'integer'],
                                        'total' => ['type' => 'integer'],
                                    ],
                                ]]],
                            ],
                        ],
                    ],
                    'post' => [
                        'tags' => ['Comments'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Cria um comentário',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['body'],
                                        'properties' => [
                                            'body' => ['type' => 'string', 'example' => 'Ótimo post!'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Comentário criado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Comment']]],
                            ],
                        ],
                    ],
                ],
                '/comments/{id}' => [
                    'put' => [
                        'tags' => ['Comments'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Atualiza um comentário',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['body'],
                                        'properties' => [
                                            'body' => ['type' => 'string', 'example' => 'Comentário atualizado'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Comentário atualizado',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Comment']]],
                            ],
                        ],
                    ],
                    'delete' => [
                        'tags' => ['Comments'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Remove um comentário',
                        'parameters' => [[
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'integer'],
                        ]],
                        'responses' => [
                            '200' => [
                                'description' => 'Comentário removido',
                                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/MessageResponse']]],
                            ],
                        ],
                    ],
                ],
                '/feed' => [
                    'get' => [
                        'tags' => ['Feed'],
                        'security' => [['bearerAuth' => []]],
                        'summary' => 'Lista o feed autenticado',
                        'responses' => [
                            '200' => [
                                'description' => 'Feed de posts',
                                'content' => ['application/json' => ['schema' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/Post'],
                                ]]],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}