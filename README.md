🧠 Theracord CRM — Client & Session Tracker

Theracord CRM is a sleek and intuitive tool designed to help therapists and counselors manage their client relationships, sessions, and payments in one place. It was built as part of my developer portfolio to showcase clean UX, reusable component structure, and thoughtful data handling.

✨ Features I’m Proud Of

🢑 Clean, Readable Client Cards

The dashboard features tidy client cards with visual icons (email, phone, notes) that make scanning for key info easy and pleasant. The design is minimal but friendly, giving the user just enough detail without being overwhelming.

📊 Client Financial Overview at a Glance

Clicking a client profile brings up a dedicated dashboard showing total sessions, amount charged, total paid (split by client and insurance), and remaining balance — all as clear info blocks. It puts the most important data front and center, which I’m really proud of.

♻️ Non-Repetitive, Contextual UI

Even though session and payment data repeat across the app, the way they’re presented feels unique to each screen. Cards, modals, and summaries are reused intentionally, giving structure without redundancy. It keeps the user engaged and the UI consistent.

🛠️ Tech Stack

Frontend: Vue 3 + Vite + Tailwind CSS

Icons: Heroicons

Data Handling: Axios API requests to backend

State: Local ref() + computed values

Layout: Fully responsive via Tailwind utility classes

Form Validation: Basic controlled forms (manual input validation planned)

🧪 What I'd Do Next

This is just the beginning — here’s where I’d love to take it next:

📝 Clicking a session should pull up full session details, including therapist notes.

💳 Clicking a payment should show breakdown (which session it’s tied to, insurance details, payment method).

✏️ Add editing ability for sessions and payments right from the client dashboard.

🗕️ Add calendar integration and scheduling features.

🔐 Secure auth for therapist login and data protection (this is currently a mock frontend).