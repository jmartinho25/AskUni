--------------------------------
-- Create the database
--------------------------------

CREATE SCHEMA IF NOT EXISTS lbaw24153;

SET search_path TO lbaw24153;



--------------------------------
-- Drop old schema
--------------------------------

DROP TABLE IF EXISTS users_roles CASCADE;
DROP TABLE IF EXISTS users_likes_posts CASCADE;
DROP TABLE IF EXISTS users_dislikes_posts CASCADE;
DROP TABLE IF EXISTS users_follow_tags CASCADE;
DROP TABLE IF EXISTS posts_tags CASCADE;
DROP TABLE IF EXISTS comments_tagging_users CASCADE;
DROP TABLE IF EXISTS users_join_super_tags CASCADE;
DROP TABLE IF EXISTS earned_badges CASCADE;
DROP TABLE IF EXISTS comments_notifications CASCADE;
DROP TABLE IF EXISTS questions_notifications CASCADE;
DROP TABLE IF EXISTS answers_notifications CASCADE;
DROP TABLE IF EXISTS badges_notifications CASCADE;
DROP TABLE IF EXISTS users_follow_questions CASCADE;
DROP TABLE IF EXISTS super_tags CASCADE;
DROP TABLE IF EXISTS content_reports CASCADE;
DROP TABLE IF EXISTS edit_histories CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS answers CASCADE;
DROP TABLE IF EXISTS questions CASCADE;
DROP TABLE IF EXISTS appeal_for_unblocks CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS roles CASCADE;
DROP TABLE IF EXISTS badges CASCADE;
DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS comments_notifications_types CASCADE;
DROP TYPE IF EXISTS questions_notifications_types CASCADE;
DROP TYPE IF EXISTS answers_notifications_types CASCADE;


--------------------------------
-- Create domains
--------------------------------
CREATE TYPE comments_notifications_types AS ENUM ('new_comment', 'comment_tagging');
CREATE TYPE questions_notifications_types AS ENUM ('tag_new_question', 'liked_question', 'disliked_question');
CREATE TYPE answers_notifications_types AS ENUM ('new_answer', 'liked_answer', 'disliked_answer');

--------------------------------
-- Create tables
--------------------------------
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE CHECK (email ~* '^[^@]+@fe\.up\.pt$'),
    password TEXT NOT NULL,
    description TEXT,
    photo TEXT DEFAULT 'profilePictures/default.jpg',
    is_blocked BOOLEAN DEFAULT FALSE,
    remember_token TEXT DEFAULT NULL,
    score INTEGER CHECK (score >= 0 AND score <= 100)
);

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE users_roles (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    roles_id INTEGER REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (users_id, roles_id)
);

CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= now()),
    users_id INTEGER REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE users_likes_posts (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    posts_id INTEGER REFERENCES posts(id) ON DELETE CASCADE,
    PRIMARY KEY (users_id, posts_id)
);

CREATE TABLE users_dislikes_posts (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    posts_id INTEGER REFERENCES posts(id) ON DELETE CASCADE,
    PRIMARY KEY (users_id, posts_id)
);

CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE super_tags (
    tags_id INTEGER REFERENCES tags(id) ON DELETE CASCADE PRIMARY KEY
);

CREATE TABLE users_join_super_tags (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    super_tags_id INTEGER REFERENCES super_tags(tags_id) ON DELETE CASCADE,
    PRIMARY KEY (users_id, super_tags_id)
);

CREATE TABLE users_follow_tags (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    tags_id INTEGER REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (users_id, tags_id)
);

CREATE TABLE posts_tags (
    posts_id INTEGER REFERENCES posts(id) ON DELETE CASCADE,
    tags_id INTEGER REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (posts_id, tags_id)
);


CREATE TABLE questions (
    posts_id INTEGER REFERENCES posts(id) ON DELETE CASCADE PRIMARY KEY,
    title TEXT NOT NULL
);

CREATE TABLE answers (
    posts_id INTEGER REFERENCES posts(id) ON DELETE CASCADE PRIMARY KEY,
    questions_id INTEGER REFERENCES questions(posts_id) ON DELETE SET NULL
);


ALTER TABLE questions
    ADD COLUMN answers_id INTEGER,
    ADD CONSTRAINT fk_answers_id FOREIGN KEY (answers_id) REFERENCES answers(posts_id) ON DELETE SET NULL;


CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= now()),
    users_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    posts_id INTEGER REFERENCES posts(id) ON DELETE SET NULL
);

CREATE TABLE users_follow_questions (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    questions_id INTEGER REFERENCES questions(posts_id) ON DELETE CASCADE,
    PRIMARY KEY (users_id, questions_id)
);

CREATE TABLE comments_tagging_users (
    comments_id INTEGER REFERENCES comments(id) ON DELETE CASCADE,
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY (comments_id, users_id)
);

CREATE TABLE badges (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE,
    description TEXT,
    icon TEXT
);

CREATE TABLE earned_badges (
    users_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    badges_id INTEGER REFERENCES badges(id) ON DELETE CASCADE,
    date TIMESTAMP NOT NULL CHECK (date <= now()),
    PRIMARY KEY (users_id, badges_id)
);

CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    date TIMESTAMP NOT NULL CHECK (date <= now()),
    users_id INTEGER REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE comments_notifications (
    notifications_id INTEGER PRIMARY KEY REFERENCES notifications(id) ON DELETE CASCADE,
    comments_id INTEGER REFERENCES comments(id) ON DELETE CASCADE,
    notifications_type comments_notifications_types NOT NULL 
);

CREATE TABLE questions_notifications (
    notifications_id INTEGER PRIMARY KEY REFERENCES notifications(id) ON DELETE CASCADE,
    questions_id INTEGER REFERENCES questions(posts_id) ON DELETE CASCADE,
    notifications_type questions_notifications_types NOT NULL
);

CREATE TABLE answers_notifications (
    notifications_id INTEGER PRIMARY KEY REFERENCES notifications(id) ON DELETE CASCADE,
    answers_id INTEGER REFERENCES answers(posts_id) ON DELETE CASCADE,
    notifications_type answers_notifications_types NOT NULL
);

CREATE TABLE badges_notifications (
    notifications_id INTEGER PRIMARY KEY REFERENCES notifications(id) ON DELETE CASCADE,
    badges_id INTEGER REFERENCES badges(id) ON DELETE CASCADE
);

CREATE TABLE appeal_for_unblocks (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    users_id INTEGER REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE content_reports (
    id SERIAL PRIMARY KEY,
    report_reason TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= now()),
    solved BOOLEAN DEFAULT FALSE,
    comments_id INTEGER REFERENCES comments(id) ON DELETE SET NULL,
    posts_id INTEGER REFERENCES posts(id) ON DELETE SET NULL
);

CREATE TABLE edit_histories (
    id SERIAL PRIMARY KEY,
    previous_content TEXT NOT NULL,
    new_content TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= now()),
    posts_id INTEGER REFERENCES posts(id) ON DELETE SET NULL,
    comments_id INTEGER REFERENCES comments(id) ON DELETE SET NULL
);


--------------------------------
-- Create Indexes
--------------------------------

CREATE INDEX users_username ON users USING btree(username);
CLUSTER users USING users_username;

CREATE INDEX posts_users_id ON posts USING btree(users_id);
CLUSTER posts USING posts_users_id;

CREATE INDEX comments_users_id ON comments USING btree(users_id);
CLUSTER comments USING comments_users_id;

CREATE INDEX questions_posts_id ON questions USING btree(posts_id);
CLUSTER questions USING questions_posts_id;

CREATE INDEX answers_posts_id ON answers USING btree(posts_id);
CLUSTER answers USING answers_posts_id;

CREATE INDEX comments_posts_id ON comments USING btree(posts_id);
CLUSTER comments USING comments_posts_id;

CREATE INDEX notifications_users_id ON notifications USING btree(users_id);
CLUSTER notifications USING notifications_users_id;

CREATE INDEX badges_name ON badges USING btree(name);
CLUSTER badges USING badges_name;


--------------------------------
-- Full-text Search Indices
--------------------------------

-- Posts Full-text Search

ALTER TABLE posts ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION posts_search_update() RETURNS trigger AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.content, '')), 'A');
        RETURN NEW;
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF NEW.content <> OLD.content THEN
            NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.content, '')), 'A');
            RETURN NEW;
        END IF;
    END IF;

    RETURN NULL;
END $$ LANGUAGE plpgsql;

CREATE TRIGGER posts_search_update BEFORE INSERT OR UPDATE ON posts
    FOR EACH ROW EXECUTE PROCEDURE posts_search_update();

CREATE INDEX posts_search_idx ON posts USING GIN(tsvectors);


-- Comments Full-text Search

ALTER TABLE comments ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION comments_search_update() RETURNS trigger AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.content, '')), 'A');
        RETURN NEW;
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF NEW.content <> OLD.content THEN
            NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.content, '')), 'A');
            RETURN NEW;
        END IF;
    END IF;

    RETURN NULL;
END $$ LANGUAGE plpgsql;

CREATE TRIGGER comments_search_update BEFORE INSERT OR UPDATE ON comments
    FOR EACH ROW EXECUTE PROCEDURE comments_search_update();

CREATE INDEX comments_search_idx ON comments USING GIN(tsvectors);


-- Users Full-text Search

ALTER TABLE users ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION users_search_update() RETURNS trigger AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.username, '')), 'A') ||
                         setweight(to_tsvector('english', coalesce(NEW.name, '')), 'B');
        RETURN NEW;
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF NEW.username <> OLD.username OR NEW.name <> OLD.name THEN
            NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.username, '')), 'A') ||
                             setweight(to_tsvector('english', coalesce(NEW.name, '')), 'B');
            RETURN NEW;
        END IF;
    END IF;

    RETURN NULL;
END $$ LANGUAGE plpgsql;

CREATE TRIGGER users_search_update BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW EXECUTE PROCEDURE users_search_update();

CREATE INDEX users_search_idx ON users USING GIN(tsvectors);


-- Tags Full-text Search

ALTER TABLE tags ADD COLUMN tsvectors TSVECTOR;
CREATE OR REPLACE FUNCTION tags_search_update() RETURNS trigger AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.name, '')), 'A');
        RETURN NEW;
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF NEW.name <> OLD.name THEN
            NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.name, '')), 'A');
            RETURN NEW;
        END IF;
    END IF;

    RETURN NULL; 
END $$ LANGUAGE plpgsql;

CREATE TRIGGER tags_search_update BEFORE INSERT OR UPDATE ON tags
    FOR EACH ROW EXECUTE PROCEDURE tags_search_update();

CREATE INDEX tags_search_idx ON tags USING GIN(tsvectors);



--------------------------------
-- Create Triggers
--------------------------------

-- TRIGGER01: Notificação de novo comentário em um post
CREATE OR REPLACE FUNCTION new_comment_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    new_notification_id INTEGER;
BEGIN
    INSERT INTO notifications (content, read_status, date, users_id)
    VALUES (
        'A new comment has been added to your post.',
        FALSE,
        NEW.date,
        (SELECT users_id FROM posts WHERE id = NEW.posts_id)
    )
    RETURNING id INTO new_notification_id;

    INSERT INTO comments_notifications (notifications_id, comments_id, notifications_type)
    VALUES (
        new_notification_id,
        NEW.id,
        'new_comment'
    );

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER new_comment_notification
    AFTER INSERT ON comments
    FOR EACH ROW
    EXECUTE PROCEDURE new_comment_notification();


-- TRIGGER02: Notificação de usuário marcado em um comentário
CREATE OR REPLACE FUNCTION comment_tagging_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    new_notification_id INTEGER;
BEGIN
    INSERT INTO notifications (content, read_status, date, users_id)
    VALUES (
        'You have been tagged in a comment.',
        FALSE,
        now(),
        NEW.users_id
    ) 
    RETURNING id INTO new_notification_id;

    INSERT INTO comments_notifications (notifications_id, comments_id, notifications_type)
    VALUES (new_notification_id, NEW.comments_id, 'comment_tagging');

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_tagging_notification
    AFTER INSERT ON comments_tagging_users
    FOR EACH ROW
    EXECUTE PROCEDURE comment_tagging_notification();


-- TRIGGER03: Notificação de nova pergunta com tag seguida
CREATE OR REPLACE FUNCTION tag_new_question_notification() RETURNS TRIGGER AS $$
DECLARE
    follower_id INTEGER;
    tag_id INTEGER;
    new_notification_id INTEGER;
BEGIN
    FOR tag_id IN
        SELECT tags_id
        FROM posts_tags
        WHERE posts_id = NEW.posts_id
    LOOP
        FOR follower_id IN
            SELECT users_id
            FROM users_follow_tags
            WHERE tags_id = tag_id
        LOOP
            INSERT INTO notifications (content, read_status, date, users_id)
            VALUES (
                CONCAT('A new question has been posted with a tag you follow: "', NEW.title, '".'),
                FALSE,
                now(),
                follower_id
            )
            RETURNING id into new_notification_id;
            
            INSERT INTO questions_notifications (notifications_id, questions_id, notifications_type)
            VALUES (new_notification_id, NEW.posts_id, 'tag_new_question');
        END LOOP;
    END LOOP;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tag_new_question_notification
AFTER INSERT ON questions
FOR EACH ROW
EXECUTE FUNCTION tag_new_question_notification();


-- TRIGGER04: Notificação de pergunta liked
CREATE OR REPLACE FUNCTION liked_question_notification() RETURNS TRIGGER AS $$
DECLARE
    question_author_id INTEGER;
    new_notification_id INTEGER;
BEGIN
    IF EXISTS (SELECT 1 FROM questions WHERE posts_id = NEW.posts_id) THEN
        SELECT users_id INTO question_author_id
        FROM posts
        WHERE id = NEW.posts_id;

        INSERT INTO notifications (content, read_status, date, users_id)
        VALUES (
            'Your question has been liked.',
            FALSE,
            now(),
            question_author_id
        )
        RETURNING id INTO new_notification_id;

        INSERT INTO questions_notifications (notifications_id, questions_id, notifications_type)
        VALUES (
            new_notification_id,
            NEW.posts_id,
            'liked_question'
        );
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER liked_question_notification
AFTER INSERT ON users_likes_posts
FOR EACH ROW
EXECUTE FUNCTION liked_question_notification();

-- TRIGGER05: Notificação de pergunta disliked
CREATE OR REPLACE FUNCTION disliked_question_notification() RETURNS TRIGGER AS $$
DECLARE
    question_author_id INTEGER;
    new_notification_id INTEGER;
BEGIN
    IF EXISTS (SELECT 1 FROM questions WHERE posts_id = NEW.posts_id) THEN
        SELECT users_id INTO question_author_id
        FROM posts
        WHERE id = NEW.posts_id;

        INSERT INTO notifications (content, read_status, date, users_id)
        VALUES (
            'Your question has been disliked.',
            FALSE,
            now(),
            question_author_id
        )
        RETURNING id INTO new_notification_id;

        INSERT INTO questions_notifications (notifications_id, questions_id, notifications_type)
        VALUES (
            new_notification_id,
            NEW.posts_id,
            'disliked_question'
        );
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER disliked_question_notification
AFTER INSERT ON users_dislikes_posts
FOR EACH ROW
EXECUTE FUNCTION disliked_question_notification();


-- TRIGGER06: Notificação de nova resposta
CREATE OR REPLACE FUNCTION new_answer_notification() RETURNS TRIGGER AS $$
DECLARE
    question_author_id INTEGER;
    new_notification_id INTEGER;
BEGIN
    SELECT users_id INTO question_author_id
    FROM posts
    WHERE id = (SELECT posts_id FROM questions WHERE posts_id = NEW.questions_id);

    INSERT INTO notifications (content, read_status, date, users_id)
    VALUES (
        'Your question has received a new answer.',
        FALSE,
        now(),
        question_author_id
    )
    RETURNING id INTO new_notification_id;

    INSERT INTO answers_notifications (notifications_id, answers_id, notifications_type)
    VALUES (
        new_notification_id,
        NEW.posts_id,
        'new_answer'
    );

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER new_answer_notification
AFTER INSERT ON answers
FOR EACH ROW
EXECUTE FUNCTION new_answer_notification();


-- TRIGGER07: Notificação de resposta liked
CREATE OR REPLACE FUNCTION liked_answer_notification() RETURNS TRIGGER AS $$
DECLARE
    answer_author_id INTEGER;
    new_notification_id INTEGER;
BEGIN
    IF EXISTS (SELECT 1 FROM answers WHERE posts_id = NEW.posts_id) THEN
        SELECT users_id INTO answer_author_id
        FROM posts
        WHERE id = NEW.posts_id;

        INSERT INTO notifications (content, read_status, date, users_id)
        VALUES (
            'Your answer has been liked.',
            FALSE,
            now(),
            answer_author_id
        )
        RETURNING id INTO new_notification_id;

        INSERT INTO answers_notifications (notifications_id, answers_id, notifications_type)
        VALUES (
            new_notification_id,
            NEW.posts_id,
            'liked_answer'
        );
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER liked_answer_notification
AFTER INSERT ON users_likes_posts
FOR EACH ROW
EXECUTE FUNCTION liked_answer_notification();


-- TRIGGER08: Notificação de resposta disliked
CREATE OR REPLACE FUNCTION disliked_answer_notification() RETURNS TRIGGER AS $$
DECLARE
    answer_author_id INTEGER;
    new_notification_id INTEGER;
BEGIN
    IF EXISTS (SELECT 1 FROM answers WHERE posts_id = NEW.posts_id) THEN
        SELECT users_id INTO answer_author_id
        FROM posts
        WHERE id = NEW.posts_id;

        INSERT INTO notifications (content, read_status, date, users_id)
        VALUES (
            'Your answer has been disliked.',
            FALSE,
            now(),
            answer_author_id
        )
        RETURNING id INTO new_notification_id;

        INSERT INTO answers_notifications (notifications_id, answers_id, notifications_type)
        VALUES (
            new_notification_id,
            NEW.posts_id,
            'disliked_answer'
        );
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER disliked_answer_notification
AFTER INSERT ON users_dislikes_posts
FOR EACH ROW
EXECUTE FUNCTION disliked_answer_notification();


-- TRIGGER09: Adicionar usuários marcados em um comentário na tabela comments_tagging_users
CREATE OR REPLACE FUNCTION add_tagged_users_to_comments() RETURNS TRIGGER AS $$
DECLARE
    tagged_username TEXT;
    user_id INTEGER;
    tag_pattern TEXT := '@([a-zA-Z0-9_.-]+)';
BEGIN
    FOR tagged_username IN SELECT (regexp_matches(NEW.content, tag_pattern, 'g'))[1] LOOP
        SELECT id INTO user_id FROM users WHERE username = tagged_username;

        IF user_id IS NOT NULL AND user_id != NEW.users_id THEN
            IF NOT EXISTS (
                SELECT 1 FROM comments_tagging_users 
                WHERE comments_id = NEW.id AND users_id = user_id
            ) THEN
                INSERT INTO comments_tagging_users (comments_id, users_id)
                VALUES (NEW.id, user_id);
            END IF;
        END IF;
    END LOOP;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER add_tagged_users_to_comments
AFTER INSERT ON comments
FOR EACH ROW
EXECUTE FUNCTION add_tagged_users_to_comments();


-- TRIGGER10: Adicionar badge ao usuário que receber 20 likes em um post
CREATE OR REPLACE FUNCTION add_badge_on_20_likes() RETURNS TRIGGER AS $$
DECLARE
    post_author_id INTEGER;
    badge_id INTEGER;
    like_count INTEGER;
BEGIN
    SELECT id INTO badge_id FROM badges WHERE id = 1;

    SELECT users_id INTO post_author_id
    FROM posts
    WHERE id = NEW.posts_id;

    SELECT COUNT(*) INTO like_count
    FROM users_likes_posts
    WHERE posts_id = NEW.posts_id;

    IF like_count = 20 THEN
        IF NOT EXISTS (
            SELECT 1 FROM earned_badges
            WHERE users_id = post_author_id AND badges_id = badge_id
        ) THEN
            INSERT INTO earned_badges (users_id, badges_id, date)
            VALUES (post_author_id, badge_id, now());
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER add_badge_on_20_likes
AFTER INSERT ON users_likes_posts
FOR EACH ROW
EXECUTE FUNCTION add_badge_on_20_likes();


-- TRIGGER11: Adicionar badge ao usuário que fizer sua primeira pergunta
CREATE OR REPLACE FUNCTION add_badge_on_first_question() RETURNS TRIGGER AS $$
DECLARE
    question_count INTEGER;
    post_author_id INTEGER;
BEGIN
    SELECT users_id INTO post_author_id FROM posts WHERE id = NEW.posts_id;

    SELECT COUNT(*) INTO question_count
    FROM questions q
    JOIN posts p ON q.posts_id = p.id
    WHERE p.users_id = (SELECT users_id FROM posts WHERE id = NEW.posts_id);

    IF question_count = 1 THEN
        IF NOT EXISTS (
            SELECT 1 FROM earned_badges
            WHERE users_id = post_author_id AND badges_id = 2
        ) THEN
            INSERT INTO earned_badges (users_id, badges_id, date)
            VALUES (post_author_id, 2, now());
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER add_badge_on_first_question
AFTER INSERT ON questions
FOR EACH ROW
EXECUTE FUNCTION add_badge_on_first_question();


-- TRIGGER12: Adicionar badge ao usuário que der a primeira resposta correta
CREATE OR REPLACE FUNCTION add_badge_on_first_correct_answer() RETURNS TRIGGER AS $$
DECLARE
    answer_author_id INTEGER;
    correct_answer_count INTEGER;
BEGIN

    SELECT users_id INTO answer_author_id
    FROM posts
    WHERE id = NEW.answers_id;

    SELECT COUNT(*) INTO correct_answer_count
    FROM questions q
    JOIN posts p ON q.answers_id = p.id
    WHERE p.users_id = answer_author_id;

    IF correct_answer_count = 1 THEN
        IF NOT EXISTS (
            SELECT 1 FROM earned_badges
            WHERE users_id = answer_author_id AND badges_id = 3
        ) THEN
            INSERT INTO earned_badges (users_id, badges_id, date)
            VALUES (answer_author_id, 3, now());
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER add_badge_on_first_correct_answer
AFTER UPDATE OF answers_id ON questions
FOR EACH ROW
WHEN (NEW.answers_id IS DISTINCT FROM OLD.answers_id)
EXECUTE FUNCTION add_badge_on_first_correct_answer();


-- TRIGGER13: Adicionar badge ao usuário
CREATE OR REPLACE FUNCTION new_badge_notification() RETURNS TRIGGER AS $$
DECLARE
    badge_name TEXT;
    notification_content TEXT;
    new_notification_id INTEGER;
BEGIN
    SELECT name INTO badge_name FROM badges WHERE id = NEW.badges_id;

    notification_content := 'Congratulations! You have earned a new badge: ' || badge_name;

    INSERT INTO notifications (content, read_status, date, users_id)
    VALUES (notification_content, FALSE, now(), NEW.users_id)
    RETURNING id INTO new_notification_id;

    INSERT INTO badges_notifications (notifications_id, badges_id)
    VALUES (new_notification_id, NEW.badges_id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER new_badge_notification
AFTER INSERT ON earned_badges
FOR EACH ROW
EXECUTE FUNCTION new_badge_notification();

-- TRIGGER14: Atualizar o score do usuário quando receber um like em um post
CREATE OR REPLACE FUNCTION increment_user_score() RETURNS TRIGGER AS $$
DECLARE
	user_id INTEGER;
	user_id_score INTEGER;
BEGIN
    SELECT users_id INTO user_id FROM posts WHERE id = NEW.posts_id;
    SELECT score INTO user_id_score FROM users WHERE id = user_id;
    IF (user_id_score < 100) 
       AND NEW.users_id <> (user_id) THEN
        UPDATE users
        SET score = score + 1
        WHERE id = user_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER trigger_increment_user_score
AFTER INSERT ON users_likes_posts
FOR EACH ROW
EXECUTE FUNCTION increment_user_score();

-- TRIGGER19: Diminuir o score do usuário quando receber um dislike em um post
CREATE OR REPLACE FUNCTION decrement_user_score() RETURNS TRIGGER AS $$
DECLARE
	user_id INTEGER;
	user_id_score INTEGER;
BEGIN
    SELECT users_id INTO user_id FROM posts WHERE id = NEW.posts_id;
    SELECT score INTO user_id_score FROM users WHERE id = user_id;
    IF (user_id_score > 0) 
       AND NEW.users_id <> (user_id) THEN
        UPDATE users
        SET score = score - 1
        WHERE id = user_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER trigger_decrement_user_score
AFTER INSERT ON users_dislikes_posts
FOR EACH ROW
EXECUTE FUNCTION decrement_user_score();

-- TRIGGER15: Manter histórico de edições em posts
CREATE OR REPLACE FUNCTION log_post_edit() RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO edit_histories (previous_content, new_content, date, posts_id)
    VALUES (OLD.content, NEW.content, now(), OLD.id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_log_post_edit
BEFORE UPDATE ON posts
FOR EACH ROW
WHEN (OLD.content IS DISTINCT FROM NEW.content)
EXECUTE FUNCTION log_post_edit();


-- TRIGGER16: Bloquear usuário ao receber muitos reports no mesmo post
CREATE OR REPLACE FUNCTION block_user_on_excessive_reports() RETURNS TRIGGER AS
$$
DECLARE
    report_count INTEGER;
BEGIN
    SELECT COUNT(*) INTO report_count
    FROM content_reports
    WHERE posts_id = NEW.posts_id;

    IF report_count > 10 THEN
        UPDATE users
        SET is_blocked = TRUE
        WHERE id = (SELECT users_id FROM posts WHERE id = NEW.posts_id);
    END IF;

    RETURN NEW;
END
$$ LANGUAGE plpgsql;
CREATE TRIGGER trigger_block_user_on_excessive_reports
AFTER INSERT ON content_reports
FOR EACH ROW
EXECUTE FUNCTION block_user_on_excessive_reports();


-- oq é q estas 2 em baixo fazem?
-- TRIGGER17: Marcar comentários como editados
CREATE OR REPLACE FUNCTION mark_comment_as_edited()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE comments SET date = now()
    WHERE id = NEW.id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_mark_comment_as_edited
AFTER UPDATE ON comments
FOR EACH ROW
WHEN (OLD.content IS DISTINCT FROM NEW.content)
EXECUTE FUNCTION mark_comment_as_edited();

-- TRIGGER18: Marcar posts como editados
CREATE OR REPLACE FUNCTION mark_post_as_edited()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE posts SET date = now()
    WHERE id = NEW.id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_mark_post_as_edited
AFTER UPDATE ON posts
FOR EACH ROW
WHEN (OLD.content IS DISTINCT FROM NEW.content)
EXECUTE FUNCTION mark_post_as_edited();


/*
-- TRIGGER14: Verificar se um usuário já deu like num post
CREATE OR REPLACE FUNCTION verify_user_like() RETURNS TRIGGER AS
$$
BEGIN
    IF EXISTS (SELECT 1 FROM users_likes_posts WHERE NEW.users_id = users_id AND NEW.posts_id = posts_id) THEN
        RAISE EXCEPTION 'A user can only like a post once';
    END IF;

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER verify_user_like
BEFORE INSERT ON users_likes_posts
FOR EACH ROW
EXECUTE PROCEDURE verify_user_like();
-- TRIGGER15: Verificar se um usuário já deu dislike num post
CREATE OR REPLACE FUNCTION verify_user_dislike() RETURNS TRIGGER AS
$$
BEGIN
    IF EXISTS (SELECT 1 FROM users_dislikes_posts WHERE NEW.users_id = users_id AND NEW.posts_id = posts_id) THEN
        RAISE EXCEPTION 'A user can only dislike a post once';
    END IF;

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER verify_user_dislike
BEFORE INSERT ON users_dislikes_posts
FOR EACH ROW
EXECUTE PROCEDURE verify_user_dislike();


*/



