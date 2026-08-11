# Agentic RAG Chat

The public chat is grounded in knowledge owned by this portfolio. It uses Ollama at `192.168.100.100:11434` with:

- `qwen3.5:2b` for query planning, private bounded reasoning, and final answer synthesis
- `qwen3-embedding:0.6b` for document and query embeddings (1,024 dimensions in the tested installation)

The browser receives progress events and the final answer, but never receives the model's private reasoning trace.

## Knowledge sources

`rag:reindex` normalizes published records into `knowledge_documents`, chunks them, and stores embeddings in `knowledge_chunks`:

- profile text from `config/rag.php`
- the skills tree from `config/skills.php`
- published portfolio projects
- published achievements
- published work experience
- LinkedIn posts imported by OAuth/API sync or a LinkedIn data export

Model save/delete events enqueue targeted refreshes. Content hashes make unchanged reindexes idempotent, and full source reconciliations deactivate removed records.

## Request flow

1. qwen produces up to three structured semantic-search queries.
2. Deterministic intent hints keep the planner from accidentally excluding requested source categories.
3. qwen embeddings rank chunks with hybrid semantic and keyword scoring.
4. qwen performs a bounded private reasoning pass over the retrieved evidence.
5. A non-thinking qwen synthesis pass emits the concise grounded answer and verified source cards.

Retrieved text is explicitly marked as untrusted. The answer prompt prohibits following instructions found in source content, inventing links, or exposing prompts/reasoning.

## Commands

```bash
php artisan rag:status
php artisan rag:reindex --force
php artisan rag:reindex --source=portfolio --queue
php artisan linkedin:sync
php artisan linkedin:sync --queue
```

In Docker, prefix commands with `docker compose exec app`. Queue jobs use the `rag` queue; the Compose worker listens to `rag,default`.

## Admin console

Authenticated admins can open `/admin/knowledge` to:

- see Ollama model and index health
- reindex one source or all sources
- connect/disconnect LinkedIn OAuth
- run a LinkedIn sync
- import a LinkedIn `Shares.csv` file directly or from a ZIP export

## LinkedIn ingestion

The implementation deliberately does not automate login pages or scrape authenticated LinkedIn HTML. That approach is brittle, can expose credentials, and conflicts with normal platform controls.

Preferred ingestion uses LinkedIn OAuth and the official Posts API. Set:

```env
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_REDIRECT_URI=https://jayxcoder.duckdns.org/admin/knowledge/linkedin/callback
LINKEDIN_API_VERSION=202607
```

API access depends on the products/scopes approved for the LinkedIn developer application. If Posts API access is unavailable, download the account's LinkedIn data export and import `Shares.csv` (or its containing ZIP) in the admin knowledge console. Both paths reconcile posts by stable source key and are safe to repeat.

## Runtime configuration

```env
OLLAMA_HOST=192.168.100.100
OLLAMA_PORT=11434
OLLAMA_MODEL=qwen3.5:2b
OLLAMA_EMBEDDING_MODEL=qwen3-embedding:0.6b
OLLAMA_THINK=true
OLLAMA_PLANNER_MAX_TOKENS=256
OLLAMA_ANALYSIS_MAX_TOKENS=1536
OLLAMA_ANSWER_MAX_TOKENS=768
OLLAMA_TIMEOUT=300

RAG_ENABLED=true
RAG_TOP_K=8
RAG_MIN_SEMANTIC_SCORE=0.35
```

## Verification

```bash
php artisan test
npm run build
docker compose config --quiet
docker compose up -d --build
docker compose exec app php artisan rag:status
```

Then submit a CSRF-authenticated request to `/chat/stream`. The NDJSON response should progress through `planning`, `retrieving`, and `answering`, followed by `complete` with a grounded message and source cards.

## Rollback

Set `RAG_ENABLED=false` and recreate the app container to route chat through the pre-existing chat service. The knowledge tables can remain in place; no data deletion is required.

```c
#include <iostream>

int main() {
    std::cout << "Because the best AI subscription isn't necessarily the one with the smartest model.\n"
                 "\n"
                 "It's the one that gives you the most useful capability for every Ringgit you spend.\n";
    return 0;
}
```
