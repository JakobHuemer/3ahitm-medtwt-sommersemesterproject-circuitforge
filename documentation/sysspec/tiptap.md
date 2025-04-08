# TipTap in Database and Frontend

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

JSON wird vom Frontend als JSON-String empfangen

[Laravel TipTap validation](https://github.com/JacobFitzp/laravel-tiptap-validation)
wird am Server für die Verifizierung von TipTap JSON des 
Clients benutzt:

```php
TiptapValidation::content()
    ->whitelist()
    ->nodes("doc", "paragraph", "text", "heading", "blockquote", "codeBlock")
    ->marks("bold", "italic", "underline", "strike", "code");

TiptapValidation::containsText()
    ->between(0, 40);
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
