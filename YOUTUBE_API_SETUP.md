# Configuração da API do YouTube

## Funcionalidade

O site agora busca automaticamente o último vídeo do canal do YouTube (@valedabencaochurch) e exibe na seção "Culto Online". 

### Comportamento:
- **Transmissão ao vivo ativa**: Exibe a live com badge "AO VIVO" piscante e autoplay
- **Sem transmissão ao vivo**: Exibe o vídeo mais recente do canal
- **Erro ou sem configuração**: Exibe vídeo padrão (hM9YbvTNOOg)
- **Cache**: Resultados são cacheados por 5 minutos para economia de requisições

## Como Configurar

### 1. Obter API Key do YouTube

1. Acesse o [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione um existente
3. Ative a **YouTube Data API v3**:
   - Vá em "APIs & Services" > "Library"
   - Busque por "YouTube Data API v3"
   - Clique em "Enable"
4. Crie credenciais:
   - Vá em "APIs & Services" > "Credentials"
   - Clique em "Create Credentials" > "API Key"
   - Copie a chave gerada

### 2. Obter o ID do Canal

O ID do canal já está configurado: `UCpwW-RzmYlG1dCiZUqXVo2g`

Para verificar ou obter um novo ID:
1. Acesse o canal no YouTube
2. Veja a URL: `https://www.youtube.com/@valedabencaochurch`
3. Para obter o ID do canal, você pode:
   - Usar a API: `https://www.googleapis.com/youtube/v3/channels?part=id&forUsername=valedabencaochurch&key=SUA_API_KEY`
   - Ou acessar o código fonte da página do canal e buscar por "channelId"

### 3. Configurar no .env

Adicione as seguintes variáveis no arquivo `.env`:

```env
YOUTUBE_API_KEY=sua_api_key_aqui
YOUTUBE_CHANNEL_ID=UCpwW-RzmYlG1dCiZUqXVo2g
```

### 4. Limpar Cache (se necessário)

Se precisar forçar a atualização do vídeo:

```bash
php artisan cache:clear
```

Ou via código:
```php
app(App\Services\YouTubeService::class)->clearCache();
```

## Limites da API

- **Quota diária**: 10.000 unidades por dia (padrão gratuito)
- **Custo por requisição**:
  - Search: 100 unidades
  - Com cache de 5 minutos: ~288 requisições/dia = 28.800 unidades
  
⚠️ **Importante**: O sistema já implementa cache para economizar quota. Não remova o cache sem necessidade.

## Estrutura do Código

### Arquivos Principais:

1. **`app/Services/YouTubeService.php`**
   - Serviço principal que busca vídeos
   - Métodos: `getLatestVideo()`, `getLiveStream()`, `getLatestUpload()`

2. **`app/Http/Controllers/Frontend/HomeController.php`**
   - Busca o vídeo e passa para a view
   - Variável: `$latestVideo`

3. **`resources/views/frontend/home.blade.php`**
   - Exibe o vídeo dinamicamente
   - Mostra badge "AO VIVO" quando está em transmissão

4. **`config/services.php`**
   - Configuração das credenciais

## Troubleshooting

### Vídeo não atualiza
- Limpe o cache: `php artisan cache:clear`
- Verifique se a API key está configurada corretamente
- Verifique os logs: `storage/logs/laravel.log`

### Erro 403 (Quota Exceeded)
- Você excedeu o limite diário da API
- Aguarde até o próximo dia (meia-noite no horário do Pacific Time)
- Considere aumentar o tempo de cache

### Sempre mostra vídeo padrão
- Verifique se `YOUTUBE_API_KEY` e `YOUTUBE_CHANNEL_ID` estão no `.env`
- Verifique se a API está ativa no Google Cloud Console
- Verifique os logs de erro

## Modo de Desenvolvimento

Se não quiser configurar a API durante o desenvolvimento, o sistema funcionará normalmente exibindo o vídeo padrão. A funcionalidade completa só é necessária em produção.
