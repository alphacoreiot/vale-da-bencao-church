# Implementação: Carrossel de Eventos Dinâmico

## Objetivo
Transformar o carrossel "Vale News" da homepage em um sistema dinâmico que consome eventos cadastrados no Gerenciador de Conteúdo.

---

## 1. Estrutura Atual

### Homepage
- URL: `http://127.0.0.1:8000/`
- Seção: "Vale News" com carrossel
- Arquivo: `resources/views/welcome.blade.php` (provavelmente)
- Dados: Estáticos (hard-coded)

### Banco de Dados
- Tabela: `sections` - Tem a seção "Eventos"
- Tabela: `section_contents` - Conteúdos dos eventos
- Campos importantes:
  - `title` - Título do evento
  - `content` - Descrição/conteúdo do evento
  - `type` - Tipo (text, video, audio, gallery)
  - `is_published` - Se está publicado
  - `published_at` - Data de publicação

---

## 2. Tarefas a Executar

### PASSO 1: Verificar estrutura atual do carrossel
- [ ] Ler arquivo `resources/views/welcome.blade.php`
- [ ] Identificar HTML/CSS do carrossel "Vale News"
- [ ] Documentar estrutura dos cards (campos usados: título, descrição, imagem, data, etc)

### PASSO 2: Verificar se existe seção "Eventos" no banco
- [ ] Verificar se existe registro na tabela `sections` com slug "eventos"
- [ ] Se não existir, criar via seeder ou migration
- [ ] Garantir que `is_active = true`

### PASSO 3: Adaptar Model/Controller
- [ ] Verificar se `Section::class` tem método para buscar eventos publicados
- [ ] No `HomeController`, adicionar query para buscar eventos:
  ```php
  $eventos = Section::where('slug', 'eventos')
      ->first()
      ->publishedContents()
      ->latest('published_at')
      ->limit(6) // ou quantidade desejada
      ->get();
  ```

### PASSO 4: Modificar View da Homepage
- [ ] Substituir cards estáticos por loop Blade:
  ```blade
  @foreach($eventos as $evento)
      <div class="carousel-item">
          <h3>{{ $evento->title }}</h3>
          <p>{{ $evento->content }}</p>
          <!-- etc -->
      </div>
  @endforeach
  ```
- [ ] Manter classes CSS originais para preservar estilo
- [ ] Adicionar fallback caso não existam eventos

### PASSO 5: Gerenciador de Conteúdo - CRUD de Eventos
- [ ] Modificar `ContentManagerController` para:
  - Listar eventos da seção "Eventos"
  - Criar novo evento (form com título, conteúdo, imagem)
  - Editar evento existente
  - Deletar evento
  - Publicar/despublicar evento
- [ ] Adicionar rota específica: `admin/content/eventos`

### PASSO 6: Upload de Imagens (se necessário)
- [ ] Verificar se carrossel usa imagens
- [ ] Se sim, integrar tabela `media`
- [ ] Adicionar campo de upload no formulário de eventos
- [ ] Salvar na pasta `storage/app/public/eventos`
- [ ] Criar symlink: `php artisan storage:link`

---

## 3. Estrutura de Dados para Eventos

### Campos do formulário no admin:
- **Título** (obrigatório) - `section_contents.title`
- **Descrição** (obrigatório) - `section_contents.content`
- **Imagem** (opcional) - `media.path`
- **Data do Evento** (opcional) - pode adicionar campo `event_date` na migration
- **Status** - `section_contents.is_published`

### Exemplo de card no carrossel:
```html
<div class="carousel-item">
    <img src="{{ asset('storage/' . $evento->media->first()->path) }}" alt="{{ $evento->title }}">
    <h3>{{ $evento->title }}</h3>
    <p>{{ Str::limit($evento->content, 150) }}</p>
    <span class="date">{{ $evento->published_at->format('d/m/Y') }}</span>
</div>
```

---

## 4. Checklist de Implementação

- [ ] **ANÁLISE**: Ler e documentar estrutura atual do carrossel
- [ ] **DATABASE**: Verificar/criar seção "Eventos" no banco
- [ ] **BACKEND**: Modificar HomeController para buscar eventos
- [ ] **FRONTEND**: Adaptar view da homepage (manter estilo, consumir dados dinâmicos)
- [ ] **ADMIN**: Criar CRUD completo de eventos no Gerenciador de Conteúdo
- [ ] **MÍDIA**: Implementar upload de imagens (se necessário)
- [ ] **TESTE**: Criar eventos no admin e verificar exibição na homepage

---

## 5. Notas Importantes

- ✅ **Manter estilo visual**: Não mudar CSS/estrutura HTML do carrossel
- ✅ **Fallback**: Se não houver eventos, mostrar mensagem ou cards padrão
- ✅ **Performance**: Usar eager loading para media: `->with('media')`
- ✅ **Segurança**: Usar `{{ }}` para escapar HTML, nunca `{!! !!}` em dados do usuário
- ✅ **Validação**: No admin, validar campos obrigatórios e tamanho de imagem

---

## Status Atual
🟡 **EM PLANEJAMENTO** - Aguardando confirmação para iniciar implementação

Última atualização: 20/11/2025
