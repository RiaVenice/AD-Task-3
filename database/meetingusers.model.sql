CREATE TABLE IF NOT EXISTS public."meeting_users" (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    role varchar(50) DEFAULT 'attendee',
    status varchar(50) DEFAULT 'invited',
    invited_at timestamp DEFAULT CURRENT_TIMESTAMP
);