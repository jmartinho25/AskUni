SET search_path TO lbaw24153;

--------------------------------
-- Populate the database
--------------------------------

INSERT INTO roles (name) VALUES 
('admin'), 
('moderator');

INSERT INTO users (username, name, email, password, description, score) VALUES 
('john_doe', 'John Doe', 'john_doe@fe.up.pt', 'hashed_password_1', 'A regular user.', 50),
('jane_smith', 'Jane Smith', 'jane_smith@fe.up.pt', 'hashed_password_2', 'An avid contributor.', 70),
('admin_user', 'Admin User', 'admin_user@fe.up.pt', 'hashed_password_admin', 'Administrator of the platform.', 100),
('alice_jones', 'Alice Jones', 'alice_jones@fe.up.pt', 'hashed_password_3', 'Loves to help others.', 60),
('bob_brown', 'Bob Brown', 'bob_brown@fe.up.pt', 'hashed_password_4', 'A tech enthusiast.', 55),
('charlie_white', 'Charlie White', 'charlie_white@fe.up.pt', 'hashed_password_5', 'A passionate coder.', 65),
('david_black', 'David Black', 'david_black@fe.up.pt', 'hashed_password_6', 'Enjoys hiking and outdoor activities.', 40),
('emma_green', 'Emma Green', 'emma_green@fe.up.pt', 'hashed_password_7', 'A graphic designer.', 75),
('frank_yellow', 'Frank Yellow', 'frank_yellow@fe.up.pt', 'hashed_password_8', 'A photographer.', 80),
('grace_pink', 'Grace Pink', 'grace_pink@fe.up.pt', 'hashed_password_9', 'A music lover.', 45),
('henry_gray', 'Henry Gray', 'henry_gray@fe.up.pt', 'hashed_password_10', 'An avid reader.', 50),
('isla_blue', 'Isla Blue', 'isla_blue@fe.up.pt', 'hashed_password_11', 'A traveler at heart.', 70),
('jack_red', 'Jack Red', 'jack_red@fe.up.pt', 'hashed_password_12', 'Enjoys sports and fitness.', 55),
('kate_violet', 'Kate Violet', 'kate_violet@fe.up.pt', 'hashed_password_13', 'An aspiring chef.', 60),
('liam_orange', 'Liam Orange', 'liam_orange@fe.up.pt', 'hashed_password_14', 'A movie buff.', 65),
('mona_cyan', 'Mona Cyan', 'mona_cyan@fe.up.pt', 'hashed_password_15', 'A passionate writer.', 50),
('noah_teal', 'Noah Teal', 'noah_teal@fe.up.pt', 'hashed_password_16', 'A gamer.', 45),
('olivia_magenta', 'Olivia Magenta', 'olivia_magenta@fe.up.pt', 'hashed_password_17', 'Loves yoga and meditation.', 80),
('peter_brown', 'Peter Brown', 'peter_brown@fe.up.pt', 'hashed_password_18', 'A nature lover.', 50),
('quinn_aqua', 'Quinn Aqua', 'quinn_aqua@fe.up.pt', 'hashed_password_19', 'A fashion enthusiast.', 65),
('ryan_purple', 'Ryan Purple', 'ryan_purple@fe.up.pt', 'hashed_password_20', 'An aspiring musician.', 55),
('sara_black', 'Sara Black', 'sara_black@fe.up.pt', 'hashed_password_21', 'A data scientist.', 90),
('tina_white', 'Tina White', 'tina_white@fe.up.pt', 'hashed_password_22', 'Loves cooking and baking.', 75),
('ursula_red', 'Ursula Red', 'ursula_red@fe.up.pt', 'hashed_password_23', 'A pet lover.', 60),
('victor_yellow', 'Victor Yellow', 'victor_yellow@fe.up.pt', 'hashed_password_24', 'A fitness trainer.', 85),
('wendy_green', 'Wendy Green', 'wendy_green@fe.up.pt', 'hashed_password_25', 'An environmentalist.', 70),
('xander_gray', 'Xander Gray', 'xander_gray@fe.up.pt', 'hashed_password_26', 'A digital marketer.', 55),
('yara_blue', 'Yara Blue', 'yara_blue@fe.up.pt', 'hashed_password_27', 'A student.', 45),
('zach_orange', 'Zach Orange', 'zach_orange@fe.up.pt', 'hashed_password_28', 'A software developer.', 80),
('brian_cyan', 'Brian Cyan', 'brian_cyan@fe.up.pt', 'hashed_password_30', 'A volunteer.', 65);

INSERT INTO users_roles (users_id, roles_id) VALUES 
(3, 1),
(2, 2),
(6, 1),
(10, 2),
(11, 2);

INSERT INTO posts (content, date, users_id) VALUES 
('What are the most effective study techniques for mastering engineering mathematics? I struggle with calculus and differential equations and would love some guidance.', now(), 1),
('Hello fellow engineers! Can anyone recommend reliable resources for understanding circuit analysis? I need help grasping complex concepts and theorems.', now(), 1),
('Can someone explain the principles behind Newton’s laws of motion? I’m trying to relate them to real-world engineering applications.', now(), 2),
('I need help with my thermodynamics course. What are the key concepts I should focus on to prepare for the upcoming exam?', now(), 2),
('What’s the difference between static and dynamic equilibrium in engineering structures? I’m having difficulty understanding their applications.', now(), 3),
('Are there any good study apps or websites specifically for learning programming languages used in engineering, like MATLAB or Python?', now(), 3),
('What strategies can I use to effectively design and analyze mechanical systems? Any recommended methodologies or best practices?', now(), 4),
('Does anyone have tips for developing strong technical writing skills? I want to improve my ability to write reports and papers for engineering classes.', now(), 4),
('How do I approach solving complex engineering problems using systems thinking? I’m trying to learn how to break down systems effectively.', now(), 5),
('What are the applications of the finite element method in engineering design? I’d like to understand its importance and practical uses.', now(), 5),
('How can I better understand control systems in engineering? I find the concepts challenging and would like additional resources to help.', now(), 6),
('Can someone explain the basics of fluid mechanics? I need help with concepts like pressure, viscosity, and flow rates.', now(), 6),
('What are some good strategies for managing time during engineering projects? I often feel overwhelmed and could use some advice.', now(), 7),
('How do I conduct a failure analysis in engineering? I want to learn the process of identifying and addressing design flaws.', now(), 7),
('What is the significance of project management in engineering? I’m curious about the methodologies and tools used in the industry.', now(), 8),
('Can anyone recommend textbooks or online courses for mastering materials science? I want to better understand the properties of different materials.', now(), 8),
('What are the key considerations when designing a structural component? I’d like to know more about load calculations and safety factors.', now(), 9),
('How do I improve my teamwork skills for group engineering projects? Collaboration is essential, and I’d like tips on how to work better with peers.', now(), 9),
('What’s the best way to prepare for an engineering internship? Any advice on what skills to focus on or how to impress employers?', now(), 10),
('Can someone explain the importance of ethics in engineering practice? I want to ensure I’m making responsible and informed decisions.', now(), 10),
('To master engineering mathematics, practice is key. Try solving a variety of problems regularly and use online platforms like Khan Academy for additional tutorials.', now(), 11),
('For circuit analysis, I recommend the textbook *Fundamentals of Electric Circuits* by Alexander and Sadiku, which provides clear explanations and examples.', now(), 12),
('Newton’s laws are fundamental in engineering; they explain how forces affect the motion of objects. Understanding their application in scenarios like vehicle dynamics is essential.', now(), 13),
('Focus on concepts like the first law of thermodynamics, heat transfer, and the Carnot cycle. Practice problems related to these topics to prepare effectively for exams.', now(), 14),
('In static equilibrium, the sum of forces and moments is zero, while dynamic equilibrium involves objects in motion with constant velocity. Understanding these helps in structural analysis.', now(), 15),
('For programming languages, platforms like Codecademy offer courses specifically in MATLAB and Python, which are widely used in engineering applications.', now(), 16),
('When designing mechanical systems, consider methods like the Design for Manufacturability (DFM) approach to simplify assembly and reduce costs.', now(), 17),
('Improving technical writing involves clarity and precision. Use templates for reports, and practice writing summaries of your projects to enhance your skills.', now(), 18),
('For complex engineering problems, start by defining the problem clearly, then break it down into manageable parts. Use tools like flowcharts to visualize the process.', now(), 19),
('The finite element method is crucial for analyzing stress and deformation in structures. It allows engineers to simulate how designs will react under various conditions.', now(), 20),
('To understand control systems, study the basic concepts of feedback loops and stability. Books like *Modern Control Engineering* by Ogata provide solid foundational knowledge.', now(), 21),
('In fluid mechanics, focus on understanding Bernoulli’s equation and the principles of laminar versus turbulent flow to grasp the fundamentals.', now(), 22),
('Managing time in engineering projects requires creating a detailed project plan with deadlines and regular check-ins to assess progress.', now(), 23),
('A failure analysis typically involves collecting data, identifying failure modes, and conducting root cause analysis to prevent future occurrences.', now(), 24),
('Project management is crucial for ensuring projects are completed on time and within budget. Familiarize yourself with tools like Gantt charts and project management software.', now(), 25),
('For materials science, consider resources like *Materials Science and Engineering: An Introduction* by Callister, which provides a comprehensive overview of material properties.', now(), 26),
('When designing structural components, ensure you calculate loads accurately and consider safety factors to account for uncertainties in your design.', now(), 27),
('To improve teamwork skills, actively listen to your peers, communicate clearly, and contribute to discussions to foster collaboration in group projects.', now(), 28),
('For internship preparation, focus on building practical skills through hands-on projects and internships. Networking with professionals can also provide valuable insights.', now(), 29),
('Ethics in engineering involves understanding the implications of your work and ensuring that your designs do not harm people or the environment.', now(), 30),
('For mastering engineering mathematics, practice consistently with problems from calculus and differential equations, and utilize resources like Khan Academy for additional tutorials.', now(), 1),
('A great resource for understanding circuit analysis is the textbook *Fundamentals of Electric Circuits* by Alexander and Sadiku, which offers clear explanations and practical examples.', now(), 2),
('Newton’s laws of motion describe the relationship between forces and motion; they apply to engineering through concepts like vehicle dynamics and structural response under load.', now(), 3),
('Focus on the first law of thermodynamics, heat transfer principles, and the Carnot cycle, as these concepts are crucial for understanding energy systems.', now(), 4),
('Static equilibrium involves forces being balanced with no movement, while dynamic equilibrium pertains to systems in motion at constant velocity; both are vital in structural analysis.', now(), 5),
('For learning programming languages like MATLAB and Python, consider using platforms like Codecademy and Coursera, which offer structured courses tailored to engineers.', now(), 6),
('To design and analyze mechanical systems effectively, employ methodologies like the Design for Manufacturability (DFM) approach and consider using simulation software.', now(), 7),
('Improving your technical writing skills can be achieved by practicing clear and concise communication, using templates for reports, and seeking feedback from peers.', now(), 8),
('Approach complex engineering problems by defining the problem, breaking it down into smaller parts, and using tools like flowcharts or systems diagrams for clarity.', now(), 9),
('The finite element method is essential for analyzing complex structures and systems; it allows for simulations of how materials will behave under various conditions.', now(), 10);

INSERT INTO tags (name) VALUES 
('LEIC'), 
('MEIC'), 
('LBAW'),
('RCOM'),
('LEGI'),
('LEM'), 
('LEC'),
('LEEC'),
('MESW'),
('LEMAT'),
('THER'),
('DYNAM'),
('FLUID'),
('STATS'),
('CAD'),
('PROJ'),
('ETHICS');

INSERT INTO super_tags (tags_id) VALUES 
(1), 
(2),
(5),
(6),
(7),
(8),
(9),
(10);

INSERT INTO users_join_super_tags (users_id, super_tags_id) VALUES 
(1, 1), 
(2, 2), 
(3, 1);

INSERT INTO users_follow_tags (users_id, tags_id) VALUES 
(1, 1), 
(2, 2), 
(3, 3);

INSERT INTO posts_tags (posts_id, tags_id) VALUES 
(1, 12),
(2, 8),
(3, 6),
(4, 11),
(5, 7),
(6, 9),
(7, 6),
(8, 1),
(9, 5),
(10, 10),
(11, 9),
(12, 13),
(13, 8),
(14, 6),
(15, 9),
(16, 10),
(17, 6),
(18, 1),
(19, 1),
(20, 17);

INSERT INTO badges (name, description) VALUES 
('20 Likes', 'Awarded for receiving 20 likes on a post.'),
('First Question', 'Awarded for asking your first question.'),
('First Correct Answer', 'Awarded for getting your answer marked as correct.');

INSERT INTO questions (posts_id, title) VALUES 
(1, 'Effective Study Techniques for Engineering Mathematics?'),
(2, 'Resources for Understanding Circuit Analysis?'),
(3, 'Principles of Newton’s Laws of Motion?'),
(4, 'Key Concepts for Thermodynamics Exam?'),
(5, 'Static vs Dynamic Equilibrium in Engineering?'),
(6, 'Study Apps for Engineering Programming Languages?'),
(7, 'Design and Analyze Mechanical Systems Strategies?'),
(8, 'Tips for Developing Technical Writing Skills?'),
(9, 'Approach for Solving Complex Engineering Problems?'),
(10, 'Applications of Finite Element Method in Design?'),
(11, 'Understanding Control Systems in Engineering?'),
(12, 'Basics of Fluid Mechanics Explained?'),
(13, 'Strategies for Time Management in Engineering Projects?'),
(14, 'How to Conduct a Failure Analysis in Engineering?'),
(15, 'Significance of Project Management in Engineering?'),
(16, 'Textbooks or Online Courses for Materials Science?'),
(17, 'Key Considerations in Structural Component Design?'),
(18, 'Improving Teamwork Skills for Group Projects?'),
(19, 'Preparing for an Engineering Internship?'),
(20, 'Importance of Ethics in Engineering Practice?');

INSERT INTO answers (posts_id, questions_id) VALUES 
(21, 1),
(22, 2),
(23, 3),
(24, 4),
(25, 5),
(26, 6),
(27, 7),
(28, 8),
(29, 9),
(30, 10),
(31, 11),
(32, 12),
(33, 13),
(34, 14),
(35, 15),
(36, 16),
(37, 17),
(38, 18),
(39, 19),
(40, 20),
(41, 1),
(42, 2),
(43, 3),
(44, 4),
(45, 5),
(46, 6),
(47, 7),
(48, 8),
(49, 9),
(50, 10);

UPDATE questions SET answers_id = 21 WHERE posts_id = 1;

INSERT INTO comments (content, date, users_id, posts_id) VALUES 
('@jane_smith @john_doe I agree.', now(), 3, 3),
('I’ve noticed that consistent practice really does help with calculus and differential equations; it’s all about building a solid foundation.', now(), 5, 1),
('It’s interesting how the choice of resources can significantly impact understanding circuit analysis. Finding the right textbook makes a huge difference.', now(), 6, 2),
('Relating Newton’s laws to real-world applications really helps solidify the concepts; it’s amazing how these principles govern so much in engineering.', now(), 7, 3),
('Thermodynamics can be quite daunting; I’ve found that focusing on a few key concepts makes it easier to grasp.', now(), 8, 4),
('Understanding the differences between static and dynamic equilibrium is crucial; it can be fascinating to see these principles in action in engineering projects.', now(), 9, 5),
('Using programming languages like MATLAB seems to be becoming increasingly important; I wonder how many engineers feel comfortable with coding.', now(), 10, 21),
('It’s intriguing how control systems can impact various engineering fields; I’d love to see more examples of their applications.', now(), 11, 22),
('Time management in engineering projects is something I’m always trying to improve; it’s a skill that can make or break a project.', now(), 12, 23),
('Conducting a thorough failure analysis is such an important step; I think it’s interesting how past failures can lead to future innovations.', now(), 13, 24),
('Project management tools really help keep everything organized; I’m curious to know what others find most effective.', now(), 14, 25);

INSERT INTO users_likes_posts (users_id, posts_id) VALUES 
(1, 1),
(2, 1),
(3, 2);

INSERT INTO users_dislikes_posts (users_id, posts_id) VALUES 
(2, 3),
(1, 2);

INSERT INTO users_follow_questions (users_id, questions_id) VALUES 
(1, 1), 
(2, 2);

INSERT INTO appeal_for_unblocks (content, users_id) VALUES 
('Please unblock me!', 1), 
('Request for review', 2);

INSERT INTO content_reports (report_reason, date, solved, comments_id, posts_id) VALUES 
('Inappropriate content', now(), FALSE, 1, NULL), 
('Spam', now(), FALSE, NULL, 2);

UPDATE posts SET content = 'What are the most effective study techniques for mastering engineering mathematics? I struggle with calculus and differential equations and would appreciate some guidance.' WHERE id = 1;
UPDATE comments SET content = 'I’ve noticed that consistent practice really does help with calculus and differential equations; it’s all about building a strong foundation.' WHERE id = 2;
