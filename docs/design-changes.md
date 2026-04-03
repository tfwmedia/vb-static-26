# Design-Änderungen 2026 (Premium Minimalist Redesign)

Dieses Dokument beschreibt die visuelle Neuausrichtung der Volksbühne Worms Website auf einen modernen, minimalistischen "Premium Apple-Like" Standard.

## Design-Philosophie
Das Ziel war eine Reduktion auf das Wesentliche (Clarity), kombiniert mit einer hochwertigen Haptik durch Tiefe und Bewegung (Glassmorphism & Motion).

### 1. Farbpalette (Neutral/Minimalist)
- **Hintergrund:** Neutrales Off-White (`#f5f5f7` - "Cloud Dancer" Stil) für eine ruhige, hochwertige Basis.
- **Typografie:** "Deep Charcoal" (`#1d1d1f`) für maximale Lesbarkeit und Apple-typischen Kontrast.
- **Akzente:** Einsatz des Volksbühne-Rots (`#c31e2e`) als Haupt-Akzentfarbe für maximale CI-Wiedererkennung.

### 2. Typografie & Hierarchie
- **System-Fonts & Serifen:** Einsatz der Serifenschrift "Playfair Display" (Google Fonts) für Headlines im Theater-Stil für mehr Eleganz. Als Fließtext (Body) kommt weiterhin die SF Pro (San Francisco) Schriftartfamilie für ein natives, performantes Look & Feel zum Einsatz.
- **Responsives Scaling:** Einsatz von `clamp()` in `rem`-Einheiten für fließende Schriftskalierung über alle Devices.
- **Bold Headlines:** Große, gut lesbare Überschriften mit leichtem Abstand für den traditionellen, aber modernen Charakter.
- **Readability:** Fließtext nutzt responsiv `1.125rem` bis `1.5rem` Lead-Texte und optimierte Zeilenhöhen (1.1 für Headlines, 1.4 - 1.6 für Fließtext).

### 3. Layout: Standard CSS Grid
- Effiziente Layouts basierend auf sauberem CSS Grid für Content-Darstellungen, umgesetzt für höchste Responsivität auf Mobile und Desktop.

### 4. Glassmorphism 2.0 (Liquid Glass)
- Der Header nutzt eine hohe Transparenz (`80%`) mit intensivem Backdrop-Blur (`20px`) und Sättigungs-Boost (`180%`).
- Dies erzeugt einen "Liquid Glass" Effekt, bei dem Inhalte beim Scrollen sanft hindurchscheinen, ohne die Navigation zu beeinträchtigen.

### 5. Motion & Scroll Animations
- **Fade-In Reveal:** Inhalte erscheinen beim Scrollen sanft mit einer leichten Verschiebung nach oben (`12px`).
- **Intersection Observer:** Performante Umsetzung ohne Performance-Einbußen.
- **Reduced Motion:** Berücksichtigt Barrierefreiheitseinstellungen (`prefers-reduced-motion`).