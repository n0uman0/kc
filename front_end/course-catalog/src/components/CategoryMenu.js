import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { selectCategory } from '../features/categoriesSlice';

const CategoryMenu = () => {
  const categories = useSelector((state) => state.categories.data);
  const selectedCategory = useSelector((state) => state.categories.selected);
  const dispatch = useDispatch();

  const parentCategories = categories.filter((category) => category.parent_id === null);

  const findSubcategories = (parentId) => {
    return categories.filter((category) => category.parent_id === parentId);
  };

  const renderCategory = (category, paddingLeft = 'pl-4') => (
    <li key={category.id}>
      <button
        onClick={() => dispatch(selectCategory(category.id))}
        className={`text-left w-full py-2 ${paddingLeft} ${selectedCategory === category.id ? 'underline' : ''}`}
      >
        {category.name} ({category.count_of_courses})
      </button>
      <ul className="pl-4">
        {findSubcategories(category.id).map((subcategory) => renderCategory(subcategory, paddingLeft + ' pl-4'))}
      </ul>
    </li>
  );

  return (
    <div className="w-1/4 sm:w-3/4 md:w-1/2 lg:w-1/4 mb-4 sm:mb-0">
      <ul>
        <li>
          <button
            onClick={() => dispatch(selectCategory(null))}
            className={`text-left w-full py-2 ${selectedCategory === null ? 'underline' : ''}`}
          >
            All
          </button>
        </li>
        {parentCategories.map((parentCategory) => renderCategory(parentCategory, ''))}
      </ul>
    </div>
  );
};

export default CategoryMenu;
