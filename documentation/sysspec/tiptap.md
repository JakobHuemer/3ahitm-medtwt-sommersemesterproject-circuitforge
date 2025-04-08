# TipTap in Database und Frontend

## Frontend

Via Vue.js

Content wird als JSON-String verwaltet (gesetzt/gelesen)

Beispiel:

```markdown
# Main Heading
## Secondary Point
**Bold statement** followed by *italicized idea*.
> Important quote or callout
- First item in a list
1. Numbered instruction with `inline code`
   [Link text](https://example.com) and ~~strikethrough text~~
```

wird zu

```json
{
  "type": "doc",
  "content": [
    {
      "type": "heading",
      "attrs": {
        "level": 1
      },
      "content": [
        {
          "type": "text",
          "text": "Main Heading"
        }
      ]
    },
    {
      "type": "heading",
      "attrs": {
        "level": 2
      },
      "content": [
        {
          "type": "text",
          "text": "Secondary Point"
        }
      ]
    },
    ...
```

Die Anzahl der Buchstaben wird mit der
[CharacterCount extension](https://tiptap.dev/docs/editor/extensions/functionality/character-count)
realisiert

## Backend

JSON wird vom Frontend als JSON-String empfangen.
In der Datenbank wird der Content als JSON-String gespeichert.

[Laravel TipTap validation](https://github.com/JacobFitzp/laravel-tiptap-validation)
wird am Server für die Verifizierung von TipTap JSON des 
Clients benutzt:

```php
TiptapValidation::content()
    ->whitelist()
    ->nodes("doc", "paragraph", "text", "heading", "blockquote", "codeBlock")
    ->marks("bold", "italic", "underline", "strike", "code");

TiptapValidation::containsText()
    ->between(0, 512);
```

welches dann mit einem Laravel Validator validiert werden kann:

```php

$data = [
    "content" => $content,
];

$validator = Validator::make($data, $rules);

if ($validator->fails()) {
    // falsch
} else {
    // richtig
}
```

### Endpoints mit TipTap Content:

#### GET

**URL Parameter**

| Parameter | Typ    | Beschreibung                           |
|-----------|--------|----------------------------------------|
| `type`    | string | Ruckgabeformat: `"json"` oder `"html"` |


Verschiedene Formate werden mit
[TipTap PHP utlity](https://tiptap.dev/docs/editor/api/utilities/tiptap-for-php)
erstellt: `json`, `html`

**Beispielantwort**

- typ: **json**
    
    ```json
    {
      "type": "doc",
      "content": [
        {
          "type": "heading",
          "attrs": {
            "level": 1
          },
          "content": [
            {
              "type": "text",
              "text": "Titel"
            }
          ]
        },
        {
          "type": "paragraph",
          "content": [
            {
              "type": "text",
              "text": "text"
            }
          ]
        }
      ]
    }
    ```

- typ: **html**

  ```html
  <h1>Titel</h1><p>text</p>
  ```
  
#### POST

**Request Body**

| Feld      | Typ  | Beschreibung              |
|-----------|------|---------------------------|
| `content` | JSON | TipTap-JSON zum Speichern |

**Antworten**

- **200 OK**: Erfolg

- **422 Unprocessable Entity**
  - Valides JSON
  - Der Serverkonfiguration nicht entsprechender TipTap Content



### Serverseitige Bereinigung von TipTap-Conent

Wird mit
[Symfony](https://symfony.com/doc/current/html_sanitizer.html)
Bereinigt nach erfolgreicher Verifizierung vom Validator