import dbActionTypes from 'constants/dbActionTypes';

const db = (state = {}, action) => {
  switch (action.type) {
    case dbActionTypes.CLICK_DATABASE_CONNECT:
      // TODO
      console.log("TODO: Connect to database.");
      return {
        ...state,
        connected: true
      };
    default:
      // TODO
      console.log("TODO: Disconnect from database.");
      return {
        ...state,
        connected: false
      };
  }
};

export default db;
