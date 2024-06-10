###########################
#         MEMBERS         #
###########################

# Get all members
SELECT id, lastName, firstName, email, password, isVerified, isActive, isMod FROM members;

###########################
#         OBJECTS         #
###########################

# Get all objects (Active and Inactive) (All types)
SELECT 
    objects.id,
    objects.title,
    objects.description,
    objects.type,
    objects.classroom,
    objects.brand,
    objects.color,
    objects.value,
    objects.status,
    objects.memberOwner_id,
    objects.memberFinder_id,
    GROUP_CONCAT(images.name) AS image_name
FROM 
    objects
LEFT JOIN 
    images ON objects.id = images.object_id;
    
# Get objects only type = Perdu and status = awaiting validation (0)
SELECT 
    objects.id,
    objects.title,
    objects.description,
    objects.type,
    objects.classroom,
    objects.brand,
    objects.color,
    objects.value,
    objects.status,
    objects.memberOwner_id,
    objects.memberFinder_id,
    GROUP_CONCAT(images.name) AS image_name
FROM 
    objects
LEFT JOIN 
    images ON objects.id = images.object_id
WHERE
	objects.type = "Perdu" AND objects.status = 0;
	
# Get all objects created by a user
SELECT 
    objects.id,
    objects.title,
    objects.description,
    objects.type,
    objects.classroom,
    objects.brand,
    objects.color,
    objects.value,
    objects.status,
    objects.memberOwner_id,
    objects.memberFinder_id,
    GROUP_CONCAT(images.name) AS image_name
FROM 
    objects
LEFT JOIN 
    images ON objects.id = images.object_id
WHERE
	objects.memberOwner_id = 12;
	
# Get all objects with user owner and user finder
SELECT 
    objects.id,
    objects.title,
    objects.description,
    objects.type,
    objects.classroom,
    objects.brand,
    objects.color,
    objects.value,
    objects.status,
    objects.memberOwner_id,
    objects.memberFinder_id,
    
    GROUP_CONCAT(images.name) AS image_name,
    
    mo.firstname AS memberOwner_firstname,
    mo.lastname AS memberOwner_lastname,
    mo.email AS memberOwner_email,
    mf.firstname AS memberFinder_firstname,
    mf.lastname AS memberFinder_lastname,
    mf.email AS memberFinder_email
FROM 
    objects
LEFT JOIN 
    images ON objects.id = images.object_id
LEFT JOIN
	members mo ON mo.id = objects.memberOwner_id
LEFT JOIN
	members mf ON mf.id = objects.memberFinder_id;