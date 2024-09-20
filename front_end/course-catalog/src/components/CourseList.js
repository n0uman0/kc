import React from 'react';
import { useSelector } from 'react-redux';

const CourseList = () => {

  const categories = useSelector((state) => state.categories.data);
  const courses = useSelector(state => state.courses.data);
  const selectedCategory = useSelector(state => state.categories.selected);
  
  const selectedCategoryDetails = categories.find(category => category.id === selectedCategory);

  const getDescendantCategoryIds = (parentId) => {
    const childCategories = categories.filter(category => category.parent_id === parentId);
    let descendantIds = [...childCategories.map(category => category.id)];
    childCategories.forEach(childCategory => {
      descendantIds = [...descendantIds, ...getDescendantCategoryIds(childCategory.id)];
    });
    return descendantIds;
  };
  
  const filteredCourses = selectedCategory
    ? courses.filter(course => {
        const categoryIds = [selectedCategoryDetails.id, ...getDescendantCategoryIds(selectedCategoryDetails.id)];
        return categoryIds.includes(course.category_id);
    })
    : courses;


  if( filteredCourses.length === 0 ) {
    return <div className="text-center text-2xl mt-4">No courses found</div>;
  } 

  return (
    <div className="w-full mx-auto mt-6">
      <h1 className="text-center text-4xl w-full font-bold">Course Catalog</h1>
      <div className="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6 w-full mt-8">
        {filteredCourses.map(course => (
          <div key={course.id} className="bg-white shadow-md rounded-lg overflow-hidden relative">
            <img src={course.preview} alt={course.name} className="w-full h-48 object-cover" />
            <div className="absolute top-0 left-0 bg-blue-500 text-white px-2 py-1" style={{ opacity: 0.8 }}>
              {course.main_category_name}
            </div>
            <div className="p-4">
              <h3 className="font-bold text-lg">{course.name}</h3>
              <p className="text-sm text-gray-500 mt-1 line-clamp-3 overflow-hidden">{course.description}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default CourseList;
