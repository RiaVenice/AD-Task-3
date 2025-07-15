CREATE TABLE IF NOT EXISTS meetings (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    title VARCHAR(256) NOT NULL,
    description TEXT,
    scheduled_at TIMESTAMP NOT NULL,
    created_by uuid REFERENCES public."users" (id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
