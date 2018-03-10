package com.alarm.cms;

import java.util.Date;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;

import com.alarm.model.Comment;
import com.alarm.model.User;
import com.alarm.service.CommentService;
import com.alarm.service.FuncService;
import com.alarm.service.UserService;

import net.sf.json.JSONObject;

@Controller
@RequestMapping("cms/comment")
public class CommentCtrl {
	
	@Autowired
	private FuncService funcService;
	
	@Autowired
	private CommentService commentService;
	
	@RequestMapping(value="/select", method=RequestMethod.GET)
	public String selectAll(){
		return "redirect:/cms/user/select/1";
	}
	
	@RequestMapping(value="/select/order/{orderBy}/ascend/{ascend}", method=RequestMethod.GET)
	public String selectAll(
			@PathVariable(value="orderBy") String orderBy,
			@PathVariable(value="ascend") String ascend
	){
		return "redirect:/cms/user/select/order/"+orderBy+"/ascend/"+ascend+"/1";
	}
	
	@RequestMapping(value="/select/{page}", method=RequestMethod.GET)
	public String selectAll(
			Model model,  
			@PathVariable(value="page") Integer page,
			@ModelAttribute("redirect") String redirect
	){
		if( redirect != null ){
			return redirect;
		}
		
		Comment comment = new Comment();
		comment.setContent("Hello world");
		comment.setDiscussId(1);
		comment.setCommentId(1);
		comment.setUserId(1);
		commentService.insert(comment);
		
		pager(model, page, "id", "desc");
		
		return "UserView";
	}
	
	@RequestMapping(value="/select/order/{orderBy}/ascend/{ascend}/{page}", method=RequestMethod.GET)
	public String selectAll(
			Model model,  
			@PathVariable(value="orderBy") String orderBy,
			@PathVariable(value="ascend") String ascend,
			@PathVariable(value="page") Integer page,
			@ModelAttribute("redirect") String redirect
	){
		if( redirect != null ){
			return redirect;
		}
		
		pager(model, page, orderBy, ascend);
		
		return "UserView";
	}
	
	@RequestMapping(value="/update/{user_id}", method=RequestMethod.GET)
	public String update(
			Model model, 
			@PathVariable("user_id") Integer user_id,
			@ModelAttribute("redirect") String redirect
	){
		if( redirect != null ){
			return redirect;
		}
		
//		User user = userService.selectByPrimaryKey(user_id);
//		if( user != null ){
//			model.addAttribute("user", user);
//			return "UserView";
//		}
		
		return "redirect:/cms/user/select";
	}
	
	@RequestMapping(value="/update/{user_id}", method=RequestMethod.POST)
	public String update(
			Model model, 
			@PathVariable("user_id") Integer user_id,
			@ModelAttribute("user") User user,
			@RequestParam("referrer") String referrer,
			@ModelAttribute("redirect") String redirect
	){
		if( redirect != null ){
			return redirect;
		}
		
//		user.setId(user_id);
//		user.setModifyDate(new Date());
//		if( userService.updateByPrimaryKey(user) == 1 ){
//			if( referrer != "" ){
//				return "redirect:"+referrer.substring(referrer.lastIndexOf("/cms/"));
//			}
//			return "redirect:/cms/user/select";
//		}
		
		return "UserView";
	}
	
	@RequestMapping(value="/delete", method=RequestMethod.POST)
	public String delete(
			Model model, 
			@RequestParam("user_id") Integer user_id,
			@ModelAttribute("redirect") String redirect
	){
		if( redirect != null ){
			return redirect;
		}
		
//		User user = userService.selectByPrimaryKey(user_id);
//		if( user != null ){
//			user.setDeleted(1);
//			userService.deleteByPrimaryKey(user);
//		}
		
		return "redirect:/cms/user/select";
	}
	
	private void pager(Model model, Integer page, String orderBy, String ascend){
//		int pageSize = 20;
//		long totalRecord = 0;
//		totalRecord = userService.selectCount();
//		int totalPage = (int)Math.ceil((double)totalRecord/pageSize);
//		
//		if( page < 1 || page > totalPage ){
//			page = 1;
//		}
//		
//		Integer offset = (page-1)*pageSize;
//		List<User> user = null;
//		user = userService.selectAll(orderBy, ascend, offset, pageSize);
//		
//		model.addAttribute("page", page);
//		model.addAttribute("totalPage", totalPage);
//		model.addAttribute("totalRecord", totalRecord);
//		model.addAttribute("user", user);
	}
	
	@ModelAttribute
	private void startup(Model model, HttpSession httpSession, HttpServletRequest request){
		funcService.modelAttribute(model, httpSession, request);
	}
}
