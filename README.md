# URVENA FIX WordPress Theme

Professionelles WordPress-Theme für URVENA FIX Reifenservice in Darmstadt.

## Features

✨ **Modern & Responsive Design** - Optimiert für alle Geräte (Desktop, Tablet, Mobile)

🚀 **Performance-Optimiert** - Schnelle Ladezeiten, sauberer Code

🔧 **Custom Post Type** - Dienstleistungen mit Icons, Preisen und Details

📧 **Custom Contact Form** - Sicheres Kontaktformular mit Nonce-Schutz

🎨 **Theme Customizer** - Einfache Anpassung über WordPress Customizer

🌍 **SEO-Ready** - Schema.org LocalBusiness Markup für lokales SEO

♿ **Accessibility** - WCAG 2.1 AA konform, semantisches HTML

🇩🇪 **Deutsche Sprache** - Vollständig in Deutsch

## Installation

### 1. Theme hochladen

1. Theme-Ordner `urvena-fix` in `/wp-content/themes/` hochladen
2. Im WordPress-Backend: **Design → Themes → URVENA FIX aktivieren**

### 2. Erforderliche Seiten erstellen

Erstellen Sie folgende Seiten mit den entsprechenden Templates:

#### Startseite
- Titel: "Startseite"
- Template: Standardmäßig (front-page.php wird automatisch verwendet)
- In **Einstellungen → Lesen** als statische Startseite festlegen

#### Dienstleistungen
- Titel: "Dienstleistungen"
- Slug: `dienstleistungen`
- Template: **Dienstleistungen**

#### Kontakt
- Titel: "Kontakt"
- Slug: `kontakt`
- Template: **Kontakt**

#### Über uns (optional)
- Titel: "Über uns"
- Slug: `ueber-uns`

### 3. Menüs einrichten

1. **Design → Menüs → Neues Menü erstellen**
2. Menü-Name: "Hauptmenü"
3. Seiten hinzufügen: Startseite, Dienstleistungen, Über uns, Kontakt
4. Menü-Position: **Hauptmenü** aktivieren
5. Menü speichern

Erstellen Sie optional ein **Footer-Menü** mit Links wie:
- Impressum
- Datenschutz
- AGB

### 4. Theme Customizer konfigurieren

Gehen Sie zu **Design → Customizer** und konfigurieren Sie:

#### Kontaktinformationen
- **Telefonnummer**: z.B. +49 6151 123456
- **E-Mail**: z.B. info@urvenafix.de
- **Adresse**: z.B. Musterstraße 123, 64283 Darmstadt
- **Google Maps Link**: Link zu Ihrem Google Maps Standort

#### Öffnungszeiten
- **Wochentags**: z.B. Mo - Fr: 08:00 - 18:00 Uhr
- **Samstag**: z.B. Sa: 09:00 - 14:00 Uhr
- **Sonntag**: z.B. So: Geschlossen

#### Social Media (optional)
- Facebook URL
- Instagram URL

#### Logo
- **Website-Icon**: Unter "Website-Icon" ein Favicon hochladen (512x512px)
- **Logo**: Unter "Logo" Ihr Firmenlogo hochladen (empfohlen: 250x80px, transparent)

### 5. Dienstleistungen anlegen

1. Im WordPress-Backend: **Dienstleistungen → Neu hinzufügen**
2. Titel eingeben (z.B. "Reifenwechsel")
3. Beschreibung im Editor eingeben
4. In der rechten Sidebar "Service-Details" ausfüllen:
   - **Preisbereich**: z.B. "ab 40€"
   - **Icon**: z.B. 🛞 (Emoji)
   - **Sortierreihenfolge**: z.B. 10 (niedrigere Zahlen zuerst)
5. Optional: Vorschaubild hinzufügen
6. Veröffentlichen

#### Beispiel-Dienstleistungen:

**Reifenwechsel** 🛞
- Preis: ab 40€
- Sortierung: 10

**Reifeneinlagerung** 📦
- Preis: ab 60€ pro Saison
- Sortierung: 20

**Achsvermessung** ⚙️
- Preis: 80€ - 120€
- Sortierung: 30

**Auswuchten** ⚖️
- Preis: ab 25€
- Sortierung: 40

**Reifenreparatur** 🔧
- Preis: ab 30€
- Sortierung: 50

**Kompletträder-Montage** 🚗
- Preis: ab 50€
- Sortierung: 60

### 6. Permalinks aktualisieren

**Wichtig!** Nach der Theme-Aktivierung:
1. **Einstellungen → Permalinks**
2. Permalink-Struktur überprüfen (empfohlen: "Beitragsname")
3. **Änderungen speichern** klicken (auch wenn nichts geändert wurde)

Dies registriert den Custom Post Type "service" korrekt.

## Theme-Struktur

```
urvena-fix/
├── style.css                 # Theme-Header
├── functions.php             # Theme-Setup
├── header.php                # Header-Template
├── footer.php                # Footer-Template
├── index.php                 # Fallback-Template
├── front-page.php            # Startseiten-Template
├── page-dienstleistungen.php # Dienstleistungen-Seite
├── page-kontakt.php          # Kontakt-Seite
├── single-service.php        # Einzelne Dienstleistung
├── inc/
│   ├── custom-post-types.php # Service CPT
│   ├── customizer.php        # Theme Customizer
│   ├── schema-markup.php     # SEO Schema
│   └── contact-handler.php   # Kontaktformular-Handler
├── assets/
│   ├── css/
│   │   └── main.css          # Haupt-Stylesheet
│   ├── js/
│   │   └── main.js           # JavaScript
│   └── images/               # Bilder
└── languages/                # Übersetzungen
```

## Anpassungen

### Farben ändern

In `assets/css/main.css` können Sie die CSS-Variablen anpassen:

```css
:root {
    --color-primary: #d32f2f;        /* Hauptfarbe (Rot) */
    --color-primary-dark: #b71c1c;   /* Dunkler Rot-Ton */
    --color-secondary: #212121;       /* Dunkelgrau */
}
```

### Hero-Texte ändern

Die Texte auf der Startseite können über den WordPress Customizer angepasst werden (in einer zukünftigen Version).

Alternativ direkt in `front-page.php` bearbeiten:
- Zeile 18: Hero-Titel
- Zeile 25: Hero-Untertitel

### E-Mail-Empfänger ändern

Kontaktformular-E-Mails werden an die im Customizer hinterlegte E-Mail-Adresse gesendet.

Standard-Fallback ist die WordPress Admin-E-Mail (**Einstellungen → Allgemein**).

## WordPress-Anforderungen

- **WordPress**: 6.0 oder höher
- **PHP**: 8.1 oder höher
- **MySQL**: 5.7 oder höher

## Browser-Support

- Chrome (letzte 2 Versionen)
- Firefox (letzte 2 Versionen)
- Safari (letzte 2 Versionen)
- Edge (letzte 2 Versionen)
- Mobile Browser (iOS Safari, Chrome Mobile)

## Empfohlene Plugins

### Must-Have:
- **Yoast SEO** oder **Rank Math** - SEO-Optimierung
- **WP Super Cache** oder **W3 Total Cache** - Performance
- **Wordfence Security** - Sicherheit
- **UpdraftPlus** - Backups

### Optional:
- **Smush** - Bildoptimierung
- **WPForms Lite** - Erweiterte Formulare
- **MonsterInsights** - Google Analytics Integration

## Sicherheit

Das Theme folgt WordPress-Sicherheits-Best-Practices:

- ✅ Nonce-Verifizierung für alle Formulare
- ✅ Input-Sanitization (sanitize_text_field, sanitize_email, etc.)
- ✅ Output-Escaping (esc_html, esc_url, esc_attr)
- ✅ Capability-Checks für Admin-Funktionen
- ✅ Prepared Statements (keine direkten SQL-Queries)
- ✅ CSRF-Schutz

## Performance-Tipps

1. **Bilder optimieren**: Vor dem Upload komprimieren (TinyPNG, ImageOptim)
2. **Caching aktivieren**: W3 Total Cache oder WP Super Cache Plugin
3. **CDN verwenden**: Cloudflare (kostenlos) für statische Assets
4. **PHP 8.1+**: Neueste PHP-Version für bessere Performance
5. **GZIP-Kompression**: In .htaccess aktivieren
6. **Lazy Loading**: Bereits im Theme integriert

## Support & Dokumentation

### Häufige Probleme

**Problem**: Dienstleistungen-Seite zeigt 404-Fehler
- **Lösung**: Permalinks neu speichern (Einstellungen → Permalinks → Speichern)

**Problem**: Kontaktformular sendet keine E-Mails
- **Lösung**: SMTP-Plugin installieren (z.B. WP Mail SMTP) für zuverlässigen E-Mail-Versand

**Problem**: Logo wird nicht angezeigt
- **Lösung**: Logo über Customizer hochladen (Design → Customizer → Logo)

**Problem**: Menü erscheint nicht
- **Lösung**: Menü erstellen und Position "Hauptmenü" zuweisen

## Credits

- **Theme-Entwicklung**: URVENA Team
- **Icons**: Emojis (keine zusätzlichen Icon-Fonts nötig)
- **Fonts**: System-Fonts (Apple System, Segoe UI, Roboto)

## Changelog

### Version 1.2.0 (2025-10-22)
- ✨ Complete mobile responsiveness overhaul
- ✨ Service card styling consistency fixes (Reifen & Zubehör)
- ✨ Mobile navigation moved to footer for better UX
- ✨ Header button optimization (side-by-side layout)
- ✨ Address updated to Mainzer Str. 70, Darmstadt
- ✨ SEO schema markup improvements
- ✨ CSS cleanup and optimization (3300+ lines)
- ✨ Enhanced mobile-first design approach
- ✨ Footer navigation with white text and clean styling
- ✨ Improved touch-friendly interface

### Version 1.1.0 (2025-10-21)
- ✨ Mobile menu enhancements
- ✨ Service preview cards optimization
- ✨ CSS performance improvements

### Version 1.0.0 (2025-10-21)
- ✨ Initial Release
- ✨ Custom Post Type für Dienstleistungen
- ✨ Custom Kontaktformular mit Nonce-Schutz
- ✨ Responsive Design
- ✨ Schema.org LocalBusiness Markup
- ✨ Theme Customizer Integration
- ✨ Deutsche Übersetzung

## Lizenz

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

