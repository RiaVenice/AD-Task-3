CREATE TABLE IF NOT EXISTS tasks (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    assigned_to uuid REFERENCES users (id),
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status VARCHAR(100) DEFAULT 'Pending',
    due_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);